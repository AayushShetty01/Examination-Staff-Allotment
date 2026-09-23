# Examination Staff Allotment System

A PHP/MySQL web application that automates examination invigilation allocation using staff availability, allocation capacity, and previous assignments.

## Problem

Manual examination invigilation allocation can become repetitive and difficult to balance as the number of examinations and staff members grows.

This project models the allocation workflow as a database-backed application and automates the assignment process.

## Core workflow

Examinations + Staff + Availability -> Allocation logic -> Examination assignments -> Database / UI

## Features

- Staff registration and management
- Examination creation and management
- Staff availability tracking
- Automatic invigilation allocation
- Allocation views
- Authentication and session-based access
- MySQL-backed CRUD workflows

## Allocation approach

The implementation uses staff availability together with previous allocation information and remaining allocation capacity to distribute staff across examination batches.

The design goal is to reduce repeated assignments while respecting the available staff pool.

This is an educational allocation heuristic rather than a formal optimization solver.

## Technology

| Layer | Technology |
|---|---|
| Backend | PHP |
| Database | MySQL |
| Frontend | HTML, CSS, JavaScript |
| CI | GitHub Actions |

## Engineering improvements

The repository has been revisited with a focus on maintainability and security:

- Centralised database connection handling
- Prepared statements in authentication and selected update workflows
- Modern password hashing for new registrations
- Legacy authentication migration on successful login
- Input validation for registration
- Automated PHP syntax checks with GitHub Actions
- Repository documentation and configuration cleanup

## Local setup

1. Install PHP and MySQL. XAMPP is suitable for local development.
2. Create a database named esas.
3. Import esas.sql.
4. Configure the local database connection.
5. Place the repository under the PHP server's web root.
6. Open index.php through the local server.

## Project status

Educational / portfolio project demonstrating database-backed web development, authentication, CRUD operations, and algorithmic allocation logic. Production deployment would require additional CSRF protection, authorization review, validation, error handling, and deployment hardening.
