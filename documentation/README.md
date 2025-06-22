# Student Skill Tracker — README

This is the main README for the Student Skill Tracker project. For detailed documentation on specific features, see the `documentation/` folder.

## Quick Start
1. Clone the repository
2. Install dependencies: `composer install && npm install`
3. Copy `.env.example` to `.env` and configure your environment
4. Run migrations: `php artisan migrate --seed`
5. Start the server: `php artisan serve`

## Key Features

### 🏆 Club Event Management (NEW)
- Club managers can create, edit, and delete events for their assigned clubs.
- Events include name, description, logo (JPEG), start/end date, event type, event type description (if "other"), and venue/location (Google Maps link).
- Event type options: workshops, seminars, contests, field events, or other (with custom description).
- Venue/location is set using a Google Maps link.
- Club managers can upload a new logo when editing an event.
- Event logos are validated and stored securely.
- Students approved for a club can view all its events in real time.
- Modern, colorful, and interactive UI for both managers and students (see `ClubManagerEvents.vue` and `ClubEvents.vue`).
- Only JPEG files are accepted for event logos.

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
- See [`documentation/CLUB_MANAGEMENT_FEATURES.md`](CLUB_MANAGEMENT_FEATURES.md) for club and event management
- See [`documentation/CLUB_MANAGER_BAN.md`](CLUB_MANAGER_BAN.md) for club manager ban/unban feature

---
For more, see the documentation folder.
