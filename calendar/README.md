# Calendar Web Application

This project is a dynamic web-based calendar that allows users to manage their personal events. It provides a month-by-month view with navigation, user authentication, and full CRUD (Create, Read, Update, Delete) functionality for events, all without page reloads.

---

## 🚀 Key Features

* **Month View Navigation:** Users can browse months forward and backward indefinitely.
* **User Accounts:** Supports user registration and secure login.
* **Personalized Events:** Registered users can add, view, edit, and delete only their own events.
* **Unregistered Access:** Unregistered users can view the calendar structure but not any events.
* **Dynamic Interactions:** All user interactions, including registration, login, and event management, are handled client-side using JavaScript and AJAX requests, ensuring a seamless user experience.
* **Persistent Data:** All user and event data is securely stored in a MySQL database.

---

## 🔒 Security Highlights

Security was a core focus in the development of this application:

* **XSS Prevention:** Input is carefully handled and output is sanitized to prevent Cross-Site Scripting attacks.
* **CSRF Protection:** Anti-Cross-Site Request Forgery tokens are implemented in all forms and AJAX requests.
* **SQL Injection Prevention:** All database queries utilize prepared statements.
* **Secure Authentication:** Passwords are securely hashed and salted before storage in the database.
* **Session Security:** Session cookies are set as HTTP-Only to mitigate session hijacking risks.
* **W3C Compliance:** The application adheres to W3C validation standards.

---

## ⚙️ Technologies Used

* **Client-Side:** JavaScript (for all dynamic UI and AJAX), HTML, CSS
* **Server-Side:** PHP
* **Database:** MySQL (for all data storage)

---

## 👤 Author

* Jenny Yu 509219 jennyjihyeyu Selina Park 508897 prksyng54
http://ec2-18-220-96-196.us-east-2.compute.amazonaws.com/~selina/Module5/ (start with login.html)

sample user username: selina | password: sss
can see grouped events for group name called "group1" userename: jenny | password: jjj
creative portion

users can share their own calendar with other users by clicking "share events" button after log in
users can make group events and allow other users to view in other's calendar view under shared events header
did not implement tag since we could not implement tag successfully within deadline
