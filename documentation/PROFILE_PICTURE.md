# Student Profile Picture Feature

## Overview
Students can now upload and update their profile picture. Only JPEG, JPG, or PNG formats are allowed. The profile picture is displayed on the student dashboard along with the student's name and role.

## How it Works
- Students can upload a profile picture from their profile page.
- Name and email are displayed but cannot be edited by the student.
- The uploaded image is stored in `storage/app/public/profile_pictures` and accessed via `public/storage/profile_pictures`.
- If no picture is uploaded, a default avatar is shown.

## Usage
1. Go to the profile page as a student.
2. Use the upload form to select a JPEG, JPG, or PNG image (max 2MB).
3. Submit the form to update your profile picture.
4. The new picture will appear on your dashboard and profile.

## Technical Details
- The `users` table has a `profile_picture` column.
- The `User` model provides a `profile_picture_url` accessor for easy display.
- The upload logic is handled in `ProfileController@updatePicture`.
- The dashboard and profile Blade views display the image using this accessor.

---

For more details, see the implementation in the `profile.blade.php`, `student/dashboard.blade.php`, and `ProfileController.php` files.
