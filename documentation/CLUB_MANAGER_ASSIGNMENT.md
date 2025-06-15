# Assign Club Managers to a Club (Admin Only)

## Overview
Admins can assign one or multiple registered club managers to any club in the system.

## How It Works
- Go to the Clubs Management page as an admin.
- Click the "Assign Managers" button for the desired club.
- A form will display all registered club managers with checkboxes.
- Select one or more managers and submit the form.
- The selected managers will be assigned to the club.

## API Usage
- **Endpoint:** `POST /api/clubs/{club}/managers`
- **Body:**
  ```json
  { "manager_ids": [1, 2, ...] }
  ```
- **Authentication:** Admin only

## Notes
- Only users with the "club manager" role can be assigned.
- Assignments can be updated at any time by submitting the form again.
