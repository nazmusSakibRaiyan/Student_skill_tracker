# Project Summary

This document provides an overview of the Student Skill Tracker application, its main features, and architecture. For feature-specific documentation, see the corresponding files in this folder.

## Main Features
- Role-based access control (Admin, Club Manager, Student)
- Comprehensive skill tracking and progress monitoring
- Club management and student organization
- Club event management (manager-only CRUD, student viewing, real-time UI, logo upload, event type, venue link, and file upload for editing)
- **Event Enrollment System** with automatic completion tracking
- Real-time student dashboard with activity tracking
- Automated skill development monitoring
- Email verification for secure onboarding

## Key Capabilities
- **Event Enrollment:** Students can enroll in seminars, workshops, and contests
- **Auto-Completion:** Events automatically mark as completed when deadlines pass
- **Progress Tracking:** Real-time dashboard updates and activity history
- **Smart Scheduling:** Hourly auto-completion ensures accurate tracking

## Structure
- **Backend:** Laravel (PHP)
- **Frontend:** Blade templates, Tailwind CSS, Vue.js components
- **Database:** SQLite (default), configurable
- **Task Scheduling:** Laravel scheduler for automated processes

## Documentation
- See `EMAIL_VERIFICATION.md` for email verification details.
- See `CLUB_MANAGEMENT_FEATURES.md` for club and event management details.
- See `EVENT_ENROLLMENT_SYSTEM.md` for comprehensive enrollment system documentation.
- Add more documentation files here as needed.
