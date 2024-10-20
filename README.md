# Project Description
This project is a web application built with PHP, MySQL, JavaScript, and CSS to display details about user enrolments in courses and their completion statuses. The application fetches enrolment data from a database and provides a user-friendly interface for viewing enrolment records, including filtering options to search by user or course.

The system supports the current dataset of 100 enrolments and is designed to handle up to 100,000 enrolments efficiently. Users have unique IDs, and each course has a unique ID and description. The completion status for each enrolment can be one of three statuses: "not started", "in progress", or "completed."

# How to Install and Run the project
Step 1: Database Setup
- Install WAMP64 (or any other local server like XAMPP) to set up a local PHP and MySQL environment.
- Clone the project repository from GitHub to your local machine.
- Start your local server (WAMP, XAMPP, etc.).
- Open PHPMyAdmin and create a new database (Eg: courseenrolments).
- Import the 'schema.sql' file from the db folder to create the tables 'Users', 'Courses', 'Enrolments'.

Step 2: Insert Data to the Database
- Update the database connection settings in PHP/dbconfig.php to match your local setup (servername, username, password, dbname).
- Run 'insert_users.php', then 'insert_courses.php', then 'insert_enrolments.php' to populate the tables with data

Step 3: View the Enrolment Report
- Open a web browser and navigate to http://localhost/<project_folder>/frontend/index.html to access the report page.
