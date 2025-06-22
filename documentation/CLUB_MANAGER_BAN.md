# Club Manager Ban/Unban Feature

## Overview
Master admins can now ban or unban a club manager from any specific club. When a club manager is banned from a club:
- They will not see or have access to that club in their dashboard or any related features.
- The ban/unban status is managed from the User Management page in the admin panel.

## How It Works
- In the User Management page, each club manager has a list of clubs they manage.
- For each club, the admin can click "Ban" to ban the manager from that club, or "Unban" to restore access.
- Banned managers are immediately restricted from accessing the club.

## Technical Details
- The `club_manager` pivot table has a `banned` boolean column.
- The `User::managedClubs()` relationship only returns clubs where the manager is not banned.
- Ban/unban actions are handled by `Admin\UserController@banClubManager` and `@unbanClubManager` via POST routes.
- UI and status are updated in `resources/views/admin/users.blade.php`.

## Usage
1. Log in as a master admin.
2. Go to the User Management page.
3. Find a club manager and use the Ban/Unban buttons for each club as needed.
4. Banned managers will lose all access to the club immediately.

---
See also: `CLUB_MANAGER_ASSIGNMENT.md` and `PROJECT_FEATURES_OVERVIEW.md` for related features.
