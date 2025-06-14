# Club Management (Admin Only)

## Overview
The Club Management feature allows only users with the `master_admin` role to create, edit, and delete clubs in the system. Club managers and students do not have access to these actions.

## Features
- **Create Club:** Admins can add new clubs by providing a unique club name.
- **Edit Club:** Admins can update the name of any club. (Future: assign roles to clubs.)
- **Delete Club:** Admins can remove clubs from the system.
- **Success Messages:** After each action (create, edit, delete), a success message is displayed.
- **Access Control:** All club management routes and actions are protected by the `role:master_admin` middleware.

## Usage
1. Log in as an admin (master_admin role).
2. Go to the Admin Dashboard and click "Manage Clubs".
3. You can view, create, edit, or delete clubs from the management page.

## Technical Details
- Controller: `App\Http\Controllers\ClubController`
- Model: `App\Models\Club`
- Views: `resources/views/admin/clubs.blade.php`, `create_club.blade.php`, `edit_club.blade.php`
- Routes: Defined in `routes/web.php` under the `role:master_admin` middleware group.
- Database: `clubs` table (migration in `database/migrations`)

---
For future enhancements, club roles and additional attributes can be added to the edit form.
