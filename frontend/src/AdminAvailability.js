import React, { useState, useEffect } from "react";
import axios from "axios";
import "./AdminAvailability.css";

function AdminAvailability() {
  const [availabilities, setAvailabilities] = useState([]);
  const [date, setDate] = useState("");
  const [startTime, setStartTime] = useState("");
  const [endTime, setEndTime] = useState("");
  const [editingId, setEditingId] = useState(null);
  const [message, setMessage] = useState("");

  const fetchAvailabilities = async () => {
    try {
      const res = await axios.get("http://127.0.0.1:8000/api/admin/availabilities");
      setAvailabilities(res.data);
    } catch (error) {
      console.error(error);
    }
  };

  useEffect(() => {
    fetchAvailabilities();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingId) {
        await axios.put(`http://127.0.0.1:8000/api/admin/availabilities/${editingId}`, {
          date,
          start_time: startTime,
          end_time: endTime,
        });
        setMessage("Availability updated successfully!");
      } else {
        await axios.post("http://127.0.0.1:8000/api/admin/availabilities", {
          date,
          start_time: startTime,
          end_time: endTime,
        });
        setMessage("Availability added successfully!");
      }

      setDate("");
      setStartTime("");
      setEndTime("");
      setEditingId(null);
      fetchAvailabilities();

      setTimeout(() => setMessage(""), 4000);
    } catch (error) {
      setMessage(error.response?.data?.message || "Something went wrong");
      setTimeout(() => setMessage(""), 4000);
    }
  };

  const handleEdit = (av) => {
    setDate(av.date);
    setStartTime(av.start_time);
    setEndTime(av.end_time);
    setEditingId(av.id);
  };

  const handleDelete = async (id) => {
    if (window.confirm("Are you sure to delete this availability?")) {
      await axios.delete(`http://127.0.0.1:8000/api/admin/availabilities/${id}`);
      fetchAvailabilities();
    }
  };

  return (
    <div className="admin-container">
      <h2>Admin: Manage Availabilities</h2>

      {message && <div className={`message ${message.includes("successfully") ? "success" : "error"}`}>{message}</div>}

      <form className="admin-form" onSubmit={handleSubmit}>
        <input type="date" value={date} onChange={(e) => setDate(e.target.value)} required />
        <input type="time" value={startTime} onChange={(e) => setStartTime(e.target.value)} required />
        <input type="time" value={endTime} onChange={(e) => setEndTime(e.target.value)} required />
        <button type="submit">{editingId ? "Update" : "Add"}</button>
      </form>

      <div className="slots-grid">
        {availabilities.length > 0 ? (
          availabilities.map((av) => (
            <div className="slot-card" key={av.id}>
              <div className="slot-info">
                <p><strong>Date:</strong> {av.date}</p>
                <p><strong>Time:</strong> {av.start_time} - {av.end_time}</p>
              </div>
              <div className="slot-actions">
                <button className="edit-btn" onClick={() => handleEdit(av)}>Edit</button>
                <button className="delete-btn" onClick={() => handleDelete(av.id)}>Delete</button>
              </div>
            </div>
          ))
        ) : (
          <p style={{ textAlign: "center", color: "#555" }}>No availabilities found.</p>
        )}
      </div>
    </div>
  );
}

export default AdminAvailability;