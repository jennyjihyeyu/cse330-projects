# Simple News Web Site

This project implements a basic news aggregation and commenting website. It allows users to register, log in, submit news stories with optional links, and comment on any story. A strong emphasis was placed on implementing key web security measures.

---

## 🚀 Features

* **User Accounts:** Users can register with a username and a **securely hashed and salted password**, then log in.
* **Story Submission:** Registered users can submit news stories, which can include a title, body, and an associated link.
* **Commenting System:** Registered users can comment on any news story.
* **Role-Based Access:**
    * **Registered Users:** Can submit, edit, and delete their own stories and comments.
    * **Unregistered Users:** Can only view stories and their associated comments.
* **Database Integration:** All application data (user information, stories, comments, links) is persistently stored in a MySQL database.

---

## 🔒 Security Measures Implemented

This project prioritizes web security, incorporating defenses against common vulnerabilities:

* **SQL Injection Prevention:** Utilizes prepared statements for all database interactions.
* **Secure Passwords:** Passwords are hashed and salted before storage to prevent rainbow table attacks.
* **CSRF Protection:** Anti-CSRF tokens are implemented in forms.
* **Server-Side Validation:** Preconditions are checked on the server-side to prevent Abuse of Functionality attacks.
* **Input Filtering & Output Escaping:** Adheres to best practices for handling user input and displaying output to prevent XSS and other issues.
* **W3C Validation:** Pages are designed to validate with no errors or warnings.

---

## 👤 Author
Jenny Yu 509219 jennyjihyeyu Selina Park 508897 prksyng54

http://ec2-18-220-96-196.us-east-2.compute.amazonaws.com/~selina/Module3/startpage.php

Creative features

allow users to change their own username
allow users to duplicate their own files
allow users to rest password by checking answer for password security question when they register
Example users

username: selina | password: sss | answer for hometown question: korea
username: jenny | password: jjj | answer for hometown question: korea
