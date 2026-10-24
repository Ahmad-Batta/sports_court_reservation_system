# Sports Court Reservation System ⚽🏀

A full-stack sports court booking system built with **PHP (PDO)**, **MySQL**, and **Bootstrap 5**. The system allows users to view court schedules and place booking requests, while providing a secure admin dashboard to manage time slots, approve/reject reservations, and control user accounts.

---

## 🚀 Features

### Client Features
- **User Authentication:** Registration and secure login with password hashing.
- **Browse Availability:** Real-time view of available court dates and time slots.
- **Booking Engine:** One-click booking with validation against double-booking.
- **My Reservations:** Dashboard showing current status of user bookings (`Pending`, `Confirmed`, `Rejected`).

### Admin Features
- **Analytical Dashboard:** Real-time overview cards and dynamic **Chart.js** visualizations.
- **Slot Management:** Add new time intervals and courts, view listings, and delete obsolete slots.
- **Reservation Approvals:** Review incoming booking requests with instant accept/reject action toggles.
- **User Management:** Full user directory with one-click account activation/deactivation controls.

---

## 🛠️ Tech Stack
- **Backend:** PHP 8.x (PDO)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5, Chart.js
- **Architecture:** MVC-inspired procedural structure with dedicated `admin` separation

---

## 📂 Project Structure
```text
├── admin/
│   ├── dashboard.php
│   ├── reservations.php
│   ├── update_status.php
│   ├── slots.php
│   ├── delete_slot.php
│   ├── users.php
│   └── toggle_user.php
├── config.php
├── index.php
├── book.php
├── login.php
├── register.php
├── logout.php
├── user_reservations.php
└── README.md
```

---

## ⚙️ Setup & Installation

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/YOUR_USERNAME/court-reservation-system.git](https://github.com/YOUR_USERNAME/court-reservation-system.git)
   ```
2. **Move to web server directory:**  
   Place the project folder inside your `htdocs` (XAMPP) or `www` (WampServer/Laragon).

3. **Database Configuration:**
   - Open phpMyAdmin and create a database named `court_reservation`.
   - Import your project SQL tables (`users`, `time_slots`, `reservations`).
   - Check `config.php` database credentials:
     ```php
     $host = 'localhost';
     $db   = 'court_reservation';
     $user = 'root';
     $pass = '';
     ```

4. **Run the Project:**  
   Open your browser and navigate to:
   ```text
   http://localhost/court-reservation-system/index.php
   ```