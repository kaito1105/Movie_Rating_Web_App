# Rotten Cucumbers - Movie Rating Web App

A PHP and MySQL-based full-stack web application developed as a course project for **CSCI327: Intro to Database Systems** (Fall 2024). Rotten Cucumbers allows users to search for movies, leave ratings and reviews, and view aggregated scores from two types of reviewers—Popcorn and Rotten—similar to Rotten Tomatoes.

## Features

- **Movie Search**: Search by title and genre using dynamic SQL query building.
- **Rating System**: Two user types (Popcorn and Rotten) can submit 1–10 ratings.
- **Review Submission**: Leave optional comments that are stored and optionally displayed.
- **Role-Based Access**:
  - **Popcorn Reviewer**: Basic review submission.
  - **Rotten Reviewer**: Higher trust level, needs admin approval.
  - **Admin**: Can approve reviews and user accounts, manage previews.
- **Genre and Actor Integration**: Movies are associated with genres, directors, and actors using relational database design.
- **SQL Practice**: Complex joins, subqueries, and CTEs (Common Table Expressions).
- **Authentication & Session Management**: Users sign in and out, and page access is controlled by role.

## Technologies Used

- **Frontend**: HTML, CSS (basic styling)
- **Backend**: PHP
- **Database**: MySQL (via phpMyAdmin)
- **Local Server**: XAMPP

## Learning Outcomes

- Designed and normalized a relational database schema using **ER diagrams**.
- Developed SQL queries for complex data retrieval and aggregation.
- Integrated MySQL with PHP to build a dynamic, user-interactive web application.
- Implemented role-based access and basic user session handling.
- Explored CRUD operations and form validation in PHP.

## Sample Screens

### Main Menu
![main_screen](https://github.com/user-attachments/assets/375e438d-df7a-4f29-ba47-36db7ba41406)

### Sign In/Sign Out
![Signin](https://github.com/user-attachments/assets/603c04ce-eeca-431a-bff6-422a79312922)

### Admin
![admin1](https://github.com/user-attachments/assets/393a94f4-fed0-4107-b18f-7dd63948841e)
![admin2](https://github.com/user-attachments/assets/152070d4-feb3-4688-86db-9c7ab8936071)
![preview](https://github.com/user-attachments/assets/3003aaf4-7f84-4457-818e-eca2dfd55b54)

### Popcorn
![porpcorn](https://github.com/user-attachments/assets/f658d416-0c3b-42e3-9574-d877f0c3e4bd)

### Rotten
![rotten](https://github.com/user-attachments/assets/39590411-7752-4a3e-b4de-ca35bb52764e)
