# HostelEase — Girls Hostel Management System

> A full-stack web application for managing girls hostel operations — built with PHP, MySQL, HTML, CSS, and JavaScript.

---

## About the Project

**HostelEase** is a Girls Hostel Management System developed as a Mini Project for the MCA III Semester at **Administrative Management College (AMC), Bangalore** under Bangalore University (2025–26).

The system simplifies hostel administration by providing a dedicated portal for both students and the warden. Students can apply for rooms, contact the warden, and track their allocation status — all from one place.

---

## Features

### Student Portal
- Secure signup and login
- View available rooms and hostels
- Apply for a room online
- View room allocation status on dashboard
- Send messages to the warden
- View warden replies
- Personal profile page

### Warden / Admin Portal
- Secure warden login
- Dashboard with live stats (total students, rooms, occupancy)
- Allocate rooms to students
- View all current allocations (with live search)
- Browse rooms by availability status
- Vacate rooms
- Reply to student messages

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, CSS3, JavaScript (Vanilla) |
| Backend | PHP 8.x |
| Database | MySQL (via phpMyAdmin) |
| Server | Apache (XAMPP) |
| Fonts | Google Fonts — Poppins, Nunito |
| Icons | FontAwesome 5 |

---

## Theme

HostelEase uses a custom cheerful color palette designed for a girls hostel environment:

- Hot Pink `#e91e8c` — primary actions
- Purple `#7c3aed` — headings and badges
- Teal `#06b6d4` — highlights and info
- Light Pink `#fdf2f8` — page background

---

## How to Run Locally

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) installed
- A browser (Chrome recommended)

### Steps

**1. Clone or download the repository**
```bash
git clone https://github.com/YOUR_USERNAME/HostelEase.git
```
Place the folder inside `C:\xampp\htdocs\`

**2. Start XAMPP**

Open XAMPP Control Panel and start **Apache** and **MySQL**

**3. Set up the database**

- Open `http://localhost/phpmyadmin`
- Create a new database named `hostelease_db`
- Click the **SQL** tab and run the SQL from the section below

**4. Configure the database connection**

Open `includes/config.inc.php` and set:
```php
$servername = "localhost";
$username   = "root";
$password   = "";              // your XAMPP MySQL password
$dbname     = "hostelease_db";
```

**5. Open the app**

Go to `http://localhost/HostelEase/` in your browser

---

## Database Setup SQL

```sql
CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_fname VARCHAR(50) NOT NULL,
  student_lname VARCHAR(50) NOT NULL,
  student_roll_no VARCHAR(20) NOT NULL UNIQUE,
  mobile_no VARCHAR(15) NOT NULL,
  department VARCHAR(50) NOT NULL,
  year_of_study VARCHAR(10) NOT NULL,
  pwd VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE managers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  manager_uname VARCHAR(50) NOT NULL UNIQUE,
  pwd VARCHAR(255) NOT NULL
);

CREATE TABLE rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_no VARCHAR(10) NOT NULL UNIQUE,
  hostel_name VARCHAR(100) NOT NULL,
  room_type VARCHAR(20) NOT NULL,
  capacity INT DEFAULT 1,
  room_status ENUM('empty','occupied') DEFAULT 'empty'
);

CREATE TABLE room_details (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_roll_no VARCHAR(20) NOT NULL,
  room_no VARCHAR(10) NOT NULL,
  hostel_name VARCHAR(100),
  room_type VARCHAR(20),
  allocation_date DATE DEFAULT (CURDATE())
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_roll_no VARCHAR(20) NOT NULL,
  message TEXT NOT NULL,
  reply TEXT,
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

After creating tables, insert a default warden account by running:
```sql
INSERT INTO managers (manager_uname, pwd)
VALUES ('warden', 'YOUR_HASHED_PASSWORD');
```
> Generate the hash by running `gen_hash.php` locally — never store plain text passwords.

---

## Project Structure

```
HostelEase/
├── index.php                  # Student login
├── signup.php                 # Student registration
├── home.php                   # Student dashboard
├── profile.php                # Student profile
├── services.php               # Browse hostels & rooms
├── application_form.php       # Apply for a room
├── contact.php                # Message the warden
├── message_user.php           # View warden replies
├── login-hostel_manager.php   # Warden login
├── manager_home.php           # Warden dashboard
├── allocate_room.php          # Allocate room to student
├── allocated_rooms.php        # View all allocations
├── empty_rooms.php            # Room status overview
├── vacate_rooms.php           # Vacate a room
├── message_hostel_manager.php # Warden replies to messages
├── includes/
│   ├── config.inc.php         # DB connection + session
│   ├── login.inc.php          # Student login logic
│   ├── signup.inc.php         # Student signup logic
│   ├── login-hostel_manager.inc.php  # Warden login logic
│   └── logout.inc.php         # Logout
└── web/
    └── css/
        ├── style.css          # Custom HostelEase theme
        └── fontawesome-all.css
```

---

## Developed By

**Sucharitha**
MCA III Semester — 2025–26
Register No: P03AC24S126032
Administrative Management College, Bangalore
Bangalore University

Under the guidance of **Mrs. Rashmi Makal**

---

## License

This project was developed for academic purposes as part of the MCA curriculum at AMC Bangalore. Not intended for commercial use.
