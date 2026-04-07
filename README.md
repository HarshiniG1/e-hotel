🏨 E-HOTEL 

...........................................................................................................................................

A full-stack database application that allows customers to search, book, and rent hotel rooms across multiple hotel chains in North America.
The system provides real-time room availability, booking management, and renting operations, while maintaining historical archives of bookings and rentings to preserve past records.


This project was developed as part of CSI2132 – Databases I

..............................................................................................................................................

Installation Steps: 

Here’s a clear step-by-step guide:
To install and run the application, the following steps should be followed:
Install XAMPP and start the Apache service.
Install PostgreSQL and pgAdmin 4.
Open pgAdmin and create a new database named e-hotels.
Execute the provided SQL script to create all tables, constraints, views, triggers, and insert the data.
Unzip the project file e-hotel-main and place the folder inside the htdocs directory of XAMPP (e.g., C:\xampp\htdocs\e-hotel-main).
Inside the project folder, create a new file named db.php.
Copy and paste the provided database connection code into db.php, and replace the username and password with your own PostgreSQL credentials.
Ensure that PostgreSQL is running and accessible.
If the database connection fails, open the php.ini file in the XAMPP directory and ensure that the lines extension=pgsql and extension=pdo_pgsql are enabled by removing the ; at the beginning of the lines. Restart Apache after making these changes.
Open a web browser and navigate to:
http://localhost/e-hotel-main/index.php
The application should now be running, allowing users to register, log in, search for rooms, and access all implemented features.

.....................................................................................................................................................

Screenshots of app: 

<img width="1877" height="898" alt="image" src="https://github.com/user-attachments/assets/cb058480-caf5-453f-9838-11f28e9543b9" />
<img width="1587" height="757" alt="image" src="https://github.com/user-attachments/assets/395b332c-4306-4a94-a7c6-f8e8c512e424" />
<img width="1778" height="858" alt="image" src="https://github.com/user-attachments/assets/ef6e664d-488e-4ad3-9dfd-d0c61c523c4a" />










