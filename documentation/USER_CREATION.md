# Add Club Managers and Students (Admin Only)

## Overview
Admins can add new club managers and students to the system through the admin interface or API.

## How It Works
- On the User Management page, under "Create New User," use the provided buttons:
  - **Add Club Manager:** Opens a form to register a new club manager.
  - **Add Student:** Opens a form to register a new student.
- Fill in the required details and submit the form to create the user.

## API Usage
- **Add Club Manager:**
  - **Endpoint:** `POST /api/users/club-manager`
  - **Body:**
    ```json
    { "name": "...", "email": "...", "password": "..." }
    ```
- **Add Student:**
  - **Endpoint:** `POST /api/users/student`
  - **Body:**
    ```json
    { "name": "...", "email": "...", "password": "..." }
    ```
- **Authentication:** Admin only

## Notes
- Email must be unique for each user.
- Password must be at least 8 characters.
- Users are assigned the appropriate role automatically.
