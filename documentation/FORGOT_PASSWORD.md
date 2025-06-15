# Forgot Password / Reset Password Feature

This project includes a custom implementation for users to reset their password securely.

## Features
- **Forgot Password Link:** Available on the login page.
- **Request Reset:** Users enter their email to receive a password reset link.
- **Reset Password:** Users follow the emailed link to set a new password.

## How It Works
1. **On the Login Page:**
   - Click the "Forgot your password?" link.
2. **Request Reset:**
   - Enter your email address and submit the form.
   - If the email exists, a reset link is sent.
3. **Reset Password:**
   - Click the link in your email.
   - Enter and confirm your new password.
   - You will be logged in automatically after resetting.

## Technical Details
- **Routes:**
  - `password.request` (GET): Show reset request form
  - `password.email` (POST): Send reset link
  - `password.reset` (GET): Show reset form
  - `password.update` (POST): Update password
- **Controllers:**
  - `ForgotPasswordController`
  - `ResetPasswordController`
- **Views:**
  - `resources/views/auth/passwords/email.blade.php`
  - `resources/views/auth/passwords/reset.blade.php`

## Configuration
- Ensure your mail settings in `config/mail.php` are correct for email delivery.

---
This flow is secure and follows Laravel best practices. For further customization, edit the views or controllers as needed.
