import React, { useState } from "react";
import axios from "axios";
import "./App.css";
import AdminAvailability from "./AdminAvailability";

function App() {
  const [isAdmin, setIsAdmin] = useState(false);

  return (
    <div>
      <button onClick={() => setIsAdmin(!isAdmin)}>
        {isAdmin ? "Go to User Booking" : "Go to Admin"}
      </button>

      {isAdmin ? <AdminAvailability /> : (
        <UserBooking />
      )}
    </div>
  );
}

function UserBooking() {
  const [date, setDate] = useState("");
  const [slots, setSlots] = useState([]);
  const [selectedSlot, setSelectedSlot] = useState(null);
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [message, setMessage] = useState("");
  const [messageType, setMessageType] = useState("");
  const [loading, setLoading] = useState(false);

  const fetchSlots = async (selectedDate) => {
    setDate(selectedDate);
    setSelectedSlot(null);
    setSlots([]);

    try {
      const res = await axios.get(
        `http://127.0.0.1:8000/api/slots?date=${selectedDate}`
      );
      setSlots(res.data.slots);
    } catch (error) {
      setMessageType("error");
      if (error.response?.status === 404) {
        setMessage("No availability found for selected date.");
      } else {
        setMessage("Something went wrong.");
      }
    }
  };

  const handleBooking = async (e) => {
    e.preventDefault();
    if (!selectedSlot) {
      setMessageType("error");
      setMessage("Please select a slot.");
      return;
    }
    try {
      setLoading(true);
      await axios.post("http://127.0.0.1:8000/api/book", {
        booking_date: date,
        start_time: selectedSlot.start,
        end_time: selectedSlot.end,
        name,
        email,
      });
      await fetchSlots(date);
      setMessageType("success");
      setMessage("Booking confirmed successfully!");
      setSelectedSlot(null);
      setName("");
      setEmail("");
      setTimeout(() => {
        setMessage("");
        setMessageType("");
      }, 4000);
    } catch (error) {
      setMessageType("error");
      setMessage(error.response?.data?.message || "Something went wrong");
      setTimeout(() => setMessage(""), 4000);
    } finally {
      setLoading(false);
    }
  };

  const closeMessage = () => {
    setMessage("");
    setMessageType("");
  };

  return (
    <div className="container">
      <div className="card">
        <h2>Schedule a Meeting</h2>

        <input
          type="date"
          className="date-input"
          min={new Date().toISOString().split("T")[0]}
          onChange={(e) => fetchSlots(e.target.value)}
        />

        {slots.length > 0 && (
          <div className="slots-grid">
            {slots.map((slot, index) => (
              <button
                key={index}
                disabled={!slot.available}
                className={`slot-btn ${
                  selectedSlot?.start === slot.start ? "selected" : ""
                } ${!slot.available ? "disabled" : ""}`}
                onClick={() => {
                  if (slot.available) {
                    setSelectedSlot(slot);
                  }
                }}
              >
                {slot.start} - {slot.end}
                {!slot.available && " (Booked)"}
              </button>
            ))}
          </div>
        )}

        {selectedSlot && (
          <form onSubmit={handleBooking} className="booking-form">
            <h4>
              {date} | {selectedSlot.start} - {selectedSlot.end}
            </h4>

            <input
              type="text"
              placeholder="Your Name"
              required
              value={name}
              onChange={(e) => setName(e.target.value)}
            />

            <input
              type="email"
              placeholder="Your Email"
              required
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />

            <button type="submit" disabled={loading}>
              {loading ? "Booking..." : "Confirm Booking"}
            </button>
          </form>
        )}

        {message && (
          <div className={`message ${messageType}`}>
            <span>{message}</span>
            <button className="close-btn" onClick={closeMessage}>
              &times;
            </button>
          </div>
        )}
      </div>
    </div>
  );
}

export default App;