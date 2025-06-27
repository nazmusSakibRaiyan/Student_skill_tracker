# Event Enrollment System

## Overview
The Event Enrollment System allows students to enroll in specific types of events (seminars, workshops, contests) and automatically tracks their completion when events end. This system provides skill tracking capabilities and progress monitoring for students.

## Features

### 1. Event Enrollment
- **Eligible Events:** Only seminars, workshops, and contests allow enrollment
- **Enrollment Requirements:**
  - User must be a student (role_id = 3)
  - User must be an approved member of the club hosting the event
  - Event must not have ended
  - User cannot already be enrolled in the event

### 2. Auto-Completion System
- **Automatic Completion:** Enrollments are automatically marked as "completed" when the event's end_date passes
- **Multiple Trigger Points:**
  - When students view club events
  - When students check their enrollment history
  - When students visit their dashboard
  - Via scheduled command (runs hourly)

### 3. Enrollment Status Tracking
- **enrolled:** Student is registered for the event
- **completed:** Event has ended and enrollment is automatically completed
- **cancelled:** Student cancelled their enrollment before the event ended

### 4. Dashboard Integration
- **Recent Activities:** Shows recent enrollment and completion activities
- **Statistics:** Displays total enrollments and completed events count
- **Real-time Updates:** Auto-completion ensures dashboard shows current status

## Database Schema

### event_enrollments Table
```sql
- id (primary key)
- event_id (foreign key to events table)
- user_id (foreign key to users table)
- status (enum: 'enrolled', 'completed', 'cancelled')
- enrolled_at (timestamp)
- completed_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)
- unique constraint on (event_id, user_id)
```

## API Endpoints

### Student Enrollment Routes
- `POST /events/{event}/enroll` - Enroll in an event
- `DELETE /events/{event}/cancel` - Cancel enrollment
- `GET /my-enrollments` - Get user's enrollment history

### Club Manager Routes
- `GET /events/{event}/enrollments` - View event enrollments
- `POST /events/{event}/enrollments/{user}/complete` - Manually mark completion

## Auto-Completion Logic

### Trigger Points
1. **EventController@index** - When viewing club events
2. **EventEnrollmentController@getUserEnrollments** - When fetching enrollments
3. **RoleTestController@studentDashboard** - When loading dashboard
4. **Scheduled Command** - Hourly via `events:auto-complete`

### Implementation
```php
// Auto-complete check
if ($enrollment->status === 'enrolled' && $event->end_date <= now()) {
    $enrollment->markAsCompleted();
}
```

### Console Command
```bash
php artisan events:auto-complete
```
- Runs hourly via Laravel scheduler
- Finds all enrolled students in ended events
- Marks them as completed
- Provides detailed output of processed enrollments

## Frontend Components

### ClubEvents.vue
- **Enrollment Button:** Shows "Enroll" for eligible events
- **Status Display:** Shows enrollment status with appropriate styling
- **Cancel Option:** Allows cancellation before event ends
- **Real-time Updates:** Reflects status changes immediately

### ClubManagerEvents.vue
- **Enrollment Count:** Displays number of enrolled students
- **Status Indicators:** Shows event status and enrollment information
- **Management Interface:** Access to view and manage enrollments

### Student Dashboard
- **Recent Activities:** Lists recent enrollments and completions
- **Statistics Cards:** Shows total enrollments and completed events
- **Activity Timeline:** Chronological view of student's event participation

## Business Rules

### Enrollment Rules
1. Only seminars, workshops, and contests allow enrollment
2. Students must be approved club members
3. Cannot enroll in past events
4. Cannot enroll twice in the same event
5. Cannot cancel completed events

### Auto-Completion Rules
1. Enrollments auto-complete when `event.end_date <= now()`
2. Only "enrolled" status enrollments are auto-completed
3. Completed enrollments are immutable
4. Auto-completion updates `completed_at` timestamp

### Permission Rules
- **Students:** Can enroll, cancel, and view their own enrollments
- **Club Managers:** Can view enrollments for their events and manually mark completions
- **Admins:** Full access to all enrollment data

## Technical Implementation

### Models
- **EventEnrollment:** Main enrollment model with relationships and status methods
- **Event:** Added enrollment relationships and helper methods
- **User:** Added enrollment relationships and activity methods

### Controllers
- **EventEnrollmentController:** Handles all enrollment operations
- **EventController:** Includes auto-completion when viewing events
- **RoleTestController:** Dashboard with auto-completion and statistics

### Console Commands
- **AutoCompleteEventEnrollments:** Batch processes ended events
- **Kernel:** Schedules hourly auto-completion

### Database
- **Migration:** Creates event_enrollments table with proper constraints
- **Relationships:** Proper foreign key relationships and cascading

## Security Considerations

### Access Control
- Role-based authorization on all endpoints
- Club membership verification for enrollments
- Manager-only access to enrollment management

### Data Integrity
- Unique constraints prevent duplicate enrollments
- Foreign key constraints ensure data consistency
- Status validation prevents invalid state transitions

### Input Validation
- Event type validation for enrollment eligibility
- Date validation prevents enrollment in past events
- User authorization checks on all operations

## Usage Examples

### Student Enrollment Flow
1. Student views club events
2. Clicks "Enroll" on eligible event
3. System validates eligibility
4. Creates enrollment record
5. Shows confirmation and updated status

### Auto-Completion Flow
1. Event end_date passes
2. Next trigger point activates auto-completion
3. System finds ended events with active enrollments
4. Marks enrollments as completed
5. Updates dashboard and statistics

### Club Manager Management
1. Manager views event enrollments
2. Sees list of enrolled students
3. Can manually mark completions if needed
4. Views enrollment statistics and reports

---

This system provides comprehensive event enrollment tracking with automatic completion, ensuring accurate skill development tracking for students while maintaining data integrity and security.
