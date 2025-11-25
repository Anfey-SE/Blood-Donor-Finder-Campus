🩸 Blood Donor Finder for Campus

A web-based platform designed to help students quickly locate compatible blood donors within the campus during emergencies. The system allows donors to register, manage their availability, and receive requests—while requestors can search, filter, and contact eligible donors instantly.

👩‍💻 Team Members
Name	Roll Number	Role
Amna Hafeez	24i-3112	Scrum Master
Humna Attique	24i-3097	Product Owner
Sara Kamran	24i-3018	Development Team
📘 Course Information

Course: Intro to Software Engineering (SE-1001)
Semester: Fall 2025
Current Iteration: 3 (Full System + Admin Features)

🎯 Problem Statement

Students often struggle to find blood donors quickly during emergencies. The Blood Donor Finder for Campus solves this by maintaining a centralized donor directory with real-time availability status, verified profiles, and request history tracking.
The system reduces response time and improves coordination in critical situations.

💡 Key Features
👤 For Users (Donors + Requestors)

Create an account with blood type

Login / Reset password

Update profile details

Search for donors (filter by blood group)

Submit blood requests

View own request history

Mark availability (Available / Not Available)

Rate donors after completion

Submit feedback

🛠 For Admin

View donor list

Verify/unverify donors

Approve/reject blood requests

Post announcements

View statistics

View recent requests

Manage donor ratings

Enable email notifications

📁 Backend Structure

Lightweight PHP backend

Text-file based data storage (donors, requests, history, notifications, ratings)

🧩 Tools & Technologies

Frontend: HTML, CSS
Backend: PHP
Database: Text Files
Version Control: Git & GitHub
Task Management: Trello

🔗 Important Links

GitHub Repository: https://github.com/Anfey-SE/Blood-Donor-Finder-Campus

Trello Board: https://trello.com/invite/b/68e693812e530b170cbf9116/ATTI20c66adfc4d44a00e1ab1e9c53a41e8e7E1A5EC8/blood-donor-finder-iteration-0

🧾 Development Progress
✔ Iteration 0 — Planning Completed

Problem statement

User stories (28)

Feature list

Roles assigned

Repository + Trello setup

✔ Iteration 1 — Basic User System Completed

Registration & Login

Donor profile update

Password reset

Blood requests

Search & filter

User availability

Donation/request history

✔ Iteration 2 — Enhanced Features Completed

Rating system

Feedback system

Recent requests

Admin dashboard basic structure

✔ Iteration 3 — Full Admin + Data Integration Completed

Announcements

Notifications

Approve/Reject requests

Donor verification

Statistics

Full UI navbar on all pages

Consistent text-file database structure

📂 Project Structure
index.html  
login.html / login.php  
register.html / register.php  
profile.html / profile.php  
request.html / request.php  
search.html / search.php  
reset.html / reset.php  
availability.html / availability.php  
rate_donor.html / rate_donor.php  
feedback.html / feedback.php  
history.html / history.php / viewHistory.php

--- Admin ---
admin.html / admin.php  
admin_dashboard.php  
announcements.html / announcements.php  
approve_requests.php  
recent_requests.php  
statistics.php  
view_donor_details.php  

--- Data Files ---
donors.txt  
requests.txt  
history.txt  
notifications.txt  

⚙ Installation & Running

Install XAMPP

Copy the project folder to:
C:/xampp/htdocs/BloodDonorFinder/

Start Apache

Open in browser:
http://localhost/BloodDonorFinder/index.html

⚖ License

This project is licensed under the MIT License, free for academic and open-source use.
