# Participant Slot Management

## Overview
The Participant Slot Management feature allows club managers to set capacity limits for enrollable events (seminars, workshops, contests). This ensures controlled participation and prevents over-enrollment while providing visual feedback on availability.

## Features

### 1. Capacity Setting
- **Optional Limits:** Club managers can set maximum participant counts for events
- **Flexible Configuration:** Limits can be set during event creation or editing
- **Unlimited Default:** Events without limits allow unlimited participants
- **Event Type Specific:** Only applies to enrollable events (seminars, workshops, contests)

### 2. Enrollment Control
- **Automatic Prevention:** System prevents enrollment when capacity is reached
- **Real-time Validation:** Checks available slots before allowing enrollment
- **Smart Messaging:** Clear feedback when events are full
- **Capacity Tracking:** Counts only active enrollments (enrolled status)

### 3. Visual Progress Tracking
- **Progress Bars:** Visual representation of enrollment capacity
- **Slot Counters:** Shows enrolled/total format (e.g., "15/20 students")
- **Status Indicators:** Clear messaging for full events and remaining slots
- **Color-coded Feedback:** Green for available, red for full capacity

## Database Schema

### Updated Events Table
```sql
ALTER TABLE events ADD COLUMN max_participants INTEGER NULL;
```

The `max_participants` field:
- **Type:** Integer, nullable
- **Purpose:** Stores maximum allowed enrollments for the event
- **Default:** NULL (unlimited participants)
- **Validation:** Must be positive integer when set

## API Enhancements

### Event Creation/Update
```http
POST /club-manager/clubs/{clubId}/events
PUT /club-manager/clubs/{clubId}/events/{eventId}

{
  "name": "Workshop Title",
  "description": "Event description",
  "event_type": "workshops",
  "max_participants": 25,  // Optional: set capacity limit
  // ... other fields
}
```

### Event Response with Slot Information
```json
{
  "id": 1,
  "name": "Workshop Title",
  "event_type": "workshops",
  "max_participants": 25,
  "enrollment_count": 18,
  "available_slots": 7,
  "is_full": false,
  "can_enroll": true
}
```

## Business Logic

### Model Methods (Event.php)
```php
// Check if event has available slots
public function hasAvailableSlots()
{
    if (!$this->max_participants) {
        return true; // No limit set
    }
    return $this->getEnrollmentCount() < $this->max_participants;
}

// Get available slots count
public function getAvailableSlots()
{
    if (!$this->max_participants) {
        return null; // No limit set
    }
    return max(0, $this->max_participants - $this->getEnrollmentCount());
}

// Check if enrollment is full
public function isFull()
{
    return $this->max_participants && 
           $this->getEnrollmentCount() >= $this->max_participants;
}
```

### Enrollment Validation
```php
// Before creating enrollment
if (!$event->hasAvailableSlots()) {
    return response()->json([
        'error' => 'This event is full. No more slots available.'
    ], 400);
}
```

## Frontend Implementation

### Club Manager Interface

#### Form Fields
```vue
<div v-if="['seminars', 'workshops', 'contests'].includes(form.event_type)" 
     class="bg-blue-50 p-3 rounded-lg border border-blue-200">
  <label class="block text-sm font-semibold text-blue-700 mb-2">
    Participant Limit (optional)
  </label>
  <input v-model.number="form.max_participants" 
         type="number" 
         min="1" 
         class="input" 
         placeholder="Maximum participants (leave empty for unlimited)" />
  <p class="text-xs text-blue-600 mt-1">
    💡 Set a limit for enrollment capacity. Leave empty for unlimited participants.
  </p>
</div>
```

#### Visual Progress Display
```vue
<div class="flex items-center justify-between text-sm mb-1">
  <span class="font-semibold text-blue-700">📊 Enrollments:</span>
  <button @click="viewEnrollments(event.id)" class="text-blue-600 hover:underline font-medium">
    {{ event.enrollment_count || 0 }}{{ event.max_participants ? `/${event.max_participants}` : '' }} students
  </button>
</div>
<div v-if="event.max_participants" class="w-full bg-gray-200 rounded-full h-2">
  <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
       :style="`width: ${Math.min(100, ((event.enrollment_count || 0) / event.max_participants) * 100)}%`">
  </div>
</div>
<div v-if="event.max_participants" class="text-xs text-gray-600 mt-1">
  <span v-if="event.enrollment_count >= event.max_participants" 
        class="text-red-600 font-semibold">🔴 Event is full</span>
  <span v-else class="text-green-600">
    {{ event.max_participants - (event.enrollment_count || 0) }} slots remaining
  </span>
</div>
```

### Student Interface

#### Enrollment Status
```vue
<div v-else-if="!event.can_enroll && !event.user_enrollment">
  <div class="text-xs text-gray-500 text-center">
    <span v-if="event.is_full">
      Event is full - no slots available
    </span>
    <!-- Other conditions... -->
  </div>
</div>
```

## User Experience

### For Club Managers
1. **Event Creation:** Optional participant limit field appears for enrollable events
2. **Visual Feedback:** Progress bars and slot counters show capacity status
3. **Easy Management:** Can view, set, or modify limits anytime
4. **Clear Status:** Immediate feedback when events reach capacity

### For Students
1. **Availability Info:** See current enrollment count and available slots
2. **Enrollment Prevention:** Cannot enroll when events are full
3. **Clear Messaging:** Understands why enrollment might be unavailable
4. **Real-time Updates:** Slot availability updates immediately

## Configuration Examples

### Unlimited Participants (Default)
```json
{
  "name": "Open Workshop",
  "event_type": "workshops",
  "max_participants": null  // No limit
}
```

### Limited Capacity Event
```json
{
  "name": "Exclusive Seminar",
  "event_type": "seminars",
  "max_participants": 15  // Limited to 15 participants
}
```

### Small Group Contest
```json
{
  "name": "Coding Challenge",
  "event_type": "contests",
  "max_participants": 8  // Small group competition
}
```

## Benefits

### Operational Benefits
- **Capacity Control:** Ensures events don't exceed physical or resource limits
- **Quality Management:** Maintains optimal student-to-resource ratios
- **Planning Aid:** Helps managers plan resources and materials
- **Fair Access:** First-come, first-served enrollment system

### User Experience Benefits
- **Transparency:** Students see availability clearly
- **Motivation:** Limited slots can increase engagement
- **Clarity:** No confusion about enrollment status
- **Efficiency:** Prevents disappointment from over-enrollment

## Technical Considerations

### Performance
- **Efficient Queries:** Enrollment counting optimized for performance
- **Real-time Updates:** Immediate slot availability reflection
- **Caching Potential:** Enrollment counts can be cached if needed

### Data Integrity
- **Validation:** Prevents negative or zero limits
- **Consistency:** Enrollment counts always accurate
- **Race Conditions:** Handled at database level with constraints

### Scalability
- **Optional Feature:** Doesn't impact existing unlimited events
- **Flexible Implementation:** Easy to enable/disable per event
- **Future Extensions:** Can support waitlists or priority enrollment

---

This feature provides comprehensive capacity management while maintaining the flexibility and ease of use that makes the event enrollment system effective for both managers and students.
