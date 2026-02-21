# Scheduler-App

Mini appointment scheduling application inspired by Calendly.  
Allows admins to manage availability slots and users to book meetings.

---

## Project Structure


scheduler-app/
├── backend/ # Laravel backend
├── frontend/ # React frontend
└── README.md


---

## Features

### Admin
- Add, update, delete availability slots
- View all availabilities in a table
- Responsive design

### User
- View available slots by date
- Book a meeting with name & email
- confirmation email is sent
- See booked/unavailable slots

---

## Tech Stack

- **Backend:** Laravel 10, PHP 8
- **Frontend:** React.js, Axios
- **Database:** MySQL

---

## Installation

### Backend
```bash
cd scheduler-app/backend
composer install
cp .env.example .env
# Configure database in .env
php artisan migrate
php artisan serve

Backend runs at: http://127.0.0.1:8000

Frontend
cd scheduler-app/frontend
npm install
npm start

Frontend runs at: http://localhost:3000

Usage

Open http://localhost:3000

Toggle between Admin and User Booking

Admin can manage availability (Add, Edit, Delete)

Users select a date, see available slots, and book

### Api End Points
| Method | URL                              | Body Parameters                                               | Description                    |
| ------ | -------------------------------- | ------------------------------------------------------------- | ------------------------------ |
| GET    | `/api/admin/availabilities`      | None                                                          | Get all availabilities         |
| POST   | `/api/admin/availabilities`      | `date` (YYYY-MM-DD), `start_time` (HH:MM), `end_time` (HH:MM) | Add availability               |
| PUT    | `/api/admin/availabilities/{id}` | `date`, `start_time`, `end_time`                              | Update availability            |
| DELETE | `/api/admin/availabilities/{id}` | None                                                          | Delete availability            |
| GET    | `/api/slots?date=YYYY-MM-DD`     | None                                                          | Get available slots for a date |
| POST   | `/api/book`                      | `booking_date`, `start_time`, `end_time`, `name`, `email`     | Book a slot                    |

1. Database Tables

Availabilities

| Column     | Type      | Description |
| ---------- | --------- | ----------- |
| id         | int       | Primary Key |
| date       | date      | Slot Date   |
| start_time | time      | Start Time  |
| end_time   | time      | End Time    |
| created_at | timestamp | Auto        |
| updated_at | timestamp | Auto        |


Bookings

| Column       | Type      | Description |
| ------------ | --------- | ----------- |
| id           | int       | Primary Key |
| booking_date | date      | Booked Date |
| start_time   | time      | Slot Start  |
| end_time     | time      | Slot End    |
| name         | string    | User Name   |
| email        | string    | User Email  |
| created_at   | timestamp | Auto        |
| updated_at   | timestamp | Auto        |

2. Backend Flow

Admin manages availability:

CRUD operations on /api/admin/availabilities

User Booking:

GET /api/slots?date=YYYY-MM-DD

POST /api/book to save booking

Response in JSON

3. Frontend Flow

Admin Panel

Form to add/update availability

Table to view, edit, delete slots

User Booking

Select date

Fetch available slots

Choose slot and submit booking form

Email sent for confirmation

Future Improvements

Calendar view for better visualization

Filtering slots by time/availability

Author

Jaya Prakash M

Email: jpduke46@gmail.com



