# Examination Staff Allotment System

A PHP/MySQL web application that generates examination invigilation allocations from available staff and scheduled examinations.

## Problem
Manually assigning invigilation duties can create uneven workloads and make it difficult to account for staff availability. This project automates the allocation process using staff availability and previous allocation counts.

## Features
- Staff registration and management
- Examination creation and management
- Availability tracking
- Automatic staff allotment across examination batches
- Allocation views
- Authentication and session-based access

## Allocation approach
The allocator prioritises staff according to their remaining allocation capacity and distributes available staff across examination batches. The implementation is intended to reduce repeated assignments while respecting availability.

## Technology
- PHP
- MySQL
- HTML/CSS/JavaScript

## Local setup
1. Install PHP and MySQL (XAMPP is suitable for local development).
2. Create a database named `esas`.
3. Import `esas.sql`.
4. Configure the local database connection for your environment.
5. Place the project under the PHP server's web root.
6. Open `index.php` through the local server.

## Project status
Educational project. Authentication, authorisation, CSRF protection, validation, and deployment configuration should be hardened further before production use.
