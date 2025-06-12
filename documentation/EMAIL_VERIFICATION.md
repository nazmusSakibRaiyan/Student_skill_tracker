# Email Verification Feature — Student Skill Tracker

## Overview
This app uses Laravel's email verification system with a custom notification and branded frontend. Only users with verified emails can access dashboards and protected features.

## How It Works
- **Registration:** User registers, receives a verification email (custom template).
- **Verification Notice:** Unverified users are redirected to a branded notice page after registration or login.
- **Resend Link:** Users can resend the verification email from the notice page.
- **Access Control:** Only verified users can access dashboards; unverified users are redirected to the notice.

## Key Files
- `app/Models/User.php` — Implements `MustVerifyEmail`, sends custom notification.
- `app/Notifications/CustomEmailVerification.php` — Customizes the verification email.
- `app/Http/Controllers/Auth/EmailVerificationController.php` — Handles verification, notice, and resend logic.
- `app/Http/Middleware/EnsureEmailIsVerified.php` — Restricts access to verified users.
- `resources/views/auth/verify-email.blade.php` — Branded verification notice page.
- `routes/web.php` — Registers verification and protected routes.

## Configuration
- **Email Driver:** Uses Mailtrap for development. For production, update `.env` with real SMTP credentials.
- **Link Expiry:** Set in `config/auth.php` (`'verification' => ['expire' => 60]`).

## Testing
1. Register a new user.
2. Check Mailtrap for the verification email.
3. Click the link to verify.
4. Only after verification can the user access dashboards.
5. Unverified users are always redirected to the verification notice.

---
For production, update your `.env` to use a real SMTP provider.
