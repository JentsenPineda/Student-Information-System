# Student Information System

Simple Student Information System (PHP + MySQL)  
This repo contains the web application files and a SQL export (`student_information.sql`) so others can recreate the database.

---

## ✅ What’s in this repo
- `index.php`, `students.php`, `courses.php`, `subjects.php`, `enrollments.php`, `grades.php`, `login.php`, `register.php`, etc.
- `config.php` — database connection (edit if needed)
- `student_information.sql` — database schema + sample data (import this)
- `css/`, `js/`, and other asset folders

---

## Prerequisites
- Windows / macOS / Linux
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + phpMyAdmin) — recommended for development
- Git (for cloning the repo) — optional if you download the zip

---

## Quick setup (Windows + XAMPP) — Step by step

1. **Copy project to `htdocs`**
   - Place the `student_system` project folder inside XAMPP's `htdocs` folder:
     ```
     C:\xampp\htdocs\student_system
     ```

2. **Start XAMPP**
   - Open XAMPP Control Panel → Start **Apache** and **MySQL**.

3. **Import the database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Click **New** (left) → enter database name `student_information` (or use any name, but then edit `config.php` accordingly) → Create.
   - With `student_information` selected, click **Import** → Choose `student_information.sql` from the repo → Click **Go**.
   - (Alternative CLI import:)
     ```bash
     mysql -u root -p student_information < student_information.sql
     ```
     (Enter password if you have one; XAMPP default is no password.)

4. **Configure database connection**
   - Open `config.php` in the project folder and confirm values:
     ```php
     <?php
     $host = "localhost";
     $user = "root";
     $pass = ""; // default XAMPP is empty
     $db   = "student_information"; // must match DB name you created
     $conn = new mysqli($host, $user, $pass, $db);
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }
     ?>
     ```
   - If you used a different DB name, change `$db`. If MySQL has a password, set `$pass`.

5. **Open the app in your browser**
   - URL:
     ```
     http://localhost/student_system/
     ```
   - Or open directly to login page:
     ```
     http://localhost/student_system/login.php
     ```

6. **Default test account**
   - If your `student_information.sql` includes a sample admin user (optional), use:
     - Username: `admin`
     - Password: `admin123`
   - If not present, register a new account via `register.php`.

7. **(Optional) Git**
   - If you want to clone the repo:
     ```bash
     git clone https://github.com/YOUR-USERNAME/YOUR-REPO.git
     ```
   - Then move the cloned `student_system` folder into `C:\xampp\htdocs\`.

---

## Troubleshooting / Common errors

- **Unknown database `student_information`**
  - You must import `student_information.sql` and/or ensure `config.php` uses the correct DB name.

- **Connection failed: Access denied**
  - Check `config.php` credentials. XAMPP default: `user = root`, `pass = ""`.

- **No CSS / unstyled pages**
  - Ensure your CSS files are present or you are using the Bootstrap CDN. If you installed `node_modules` locally, link to the correct path or use the CDN.

- **"Call to a member function query() on null"**
  - Means `$conn` is not defined — check `config.php` include path and DB connection.

- **Data visible across accounts**
  - The app uses `user_id` in tables (users-specific). Make sure you imported the correct SQL that includes `user_id` columns.

---

## Security & notes
- Passwords are stored as MD5 in sample SQL. **MD5 is insecure** — for production, use `password_hash()` and `password_verify()`.
- The provided code is for learning/demo purposes. Do not deploy as-is to a public server.

---

## Recommended `.gitignore` (put this in your project root)
