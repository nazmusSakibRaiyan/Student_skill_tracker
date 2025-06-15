# Club Management Features Documentation

## 1. Club Manager Features
- **Update Club Info:**
  - Club managers can update their assigned club's name, description, and logo (PNG/JPG/JPEG only).
  - Logos are displayed in the dashboard and for students.
- **Add Students to Club:**
  - Club managers can search and select one or multiple registered students (by name/email) to add to their club.
  - Added students are set as "pending" and require admin approval.
- **Remove Students:**
  - Club managers can remove any student (pending or approved) from their club.

## 2. Admin Features
- **Approve/Reject Students:**
  - Admins can view all pending student approvals for clubs directly on the user management page.
  - Admins can approve or reject each student for club membership.
- **Assign Club Managers:**
  - Admins can assign club managers to clubs (existing feature).

## 3. Student Features
- **View Assigned Clubs:**
  - Students see a "My Clubs" section listing all clubs they are participating in, with logo and approval status.
  - Students can click a club to view its logo and description.

## 4. Technical Notes
- **Logo Storage:**
  - Logos are stored in `storage/app/public/club_logos` and served via the `public/storage` symlink.
- **Validation:**
  - Logo uploads are validated for file type and size.
  - Only registered students not already in the club can be added.
- **Security:**
  - Club managers can only manage their assigned clubs.
  - Students can only view clubs they are members of.

---
For further customization or troubleshooting, see the relevant Blade views, controllers, and routes.
