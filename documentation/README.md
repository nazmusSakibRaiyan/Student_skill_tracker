# Student Skill Tracker — README

This is the main README for the Student Skill Tracker project. For detailed documentation on specific features, see the `documentation/` folder.

## Quick Start
1. Clone the repository
2. Install dependencies: `composer install && npm install`
3. Copy `.env.example` to `.env` and configure your environment
4. Run migrations: `php artisan migrate --seed`
5. Start the server: `php artisan serve`

## Key Features

### 👤 Club Manager Management
- Admins can assign one or more club managers to any club.
- Club managers can be added via the admin UI or API.
- Club managers see a dashboard listing all clubs they manage.
- See [`documentation/CLUB_MANAGER_ASSIGNMENT.md`](CLUB_MANAGER_ASSIGNMENT.md) for details.

### 🎓 Student Management
- Admins can add new students via the admin UI or API.
- Students are assigned the correct role automatically.
- See [`documentation/USER_CREATION.md`](USER_CREATION.md) for details.

## Documentation
- See [`documentation/EMAIL_VERIFICATION.md`](EMAIL_VERIFICATION.md) for email verification
- See [`documentation/PROJECT_SUMMARY.md`](PROJECT_SUMMARY.md) for project overview
- See [`documentation/CLUB_MANAGER_ASSIGNMENT.md`](CLUB_MANAGER_ASSIGNMENT.md) for club manager assignment
- See [`documentation/USER_CREATION.md`](USER_CREATION.md) for user creation
- See [`documentation/PROFILE_PICTURE.md`](PROFILE_PICTURE.md) for profile picture feature

---
For more, see the documentation folder.
