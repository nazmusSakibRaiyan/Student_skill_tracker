<template>
  <div>
    <div class="mb-6 flex justify-center">
      <button @click="showEvents = !showEvents" class="px-6 py-2 bg-gradient-to-r from-pink-500 via-orange-400 to-yellow-400 text-white rounded-full shadow-lg hover:from-pink-600 hover:to-yellow-500 transition-all duration-200 text-lg font-bold">
        {{ showEvents ? 'Hide Events' : 'View Club Events' }}
      </button>
    </div>
    <div v-if="showEvents">
      <h2 class="text-2xl font-bold mb-4 text-pink-700">Club Events</h2>
      <div v-if="loading" class="text-center py-8">Loading events...</div>
      <div v-else>
        <div v-if="events.length === 0" class="text-gray-500">No events found for this club.</div>
        <div v-else class="grid gap-6 md:grid-cols-2">
          <div v-for="event in events" :key="event.id" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition border-2 border-pink-200">
            <div class="flex items-center mb-2">
              <img v-if="event.logo" :src="`/storage/${event.logo}`" alt="Event Logo" class="h-12 w-12 rounded mr-3">
              <div v-else class="h-12 w-12 bg-pink-100 rounded mr-3 flex items-center justify-center text-xl text-pink-400">
                {{ event.name.charAt(0).toUpperCase() }}
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-pink-700">{{ event.name }}</h3>
                <div class="text-xs text-gray-400">{{ formatDate(event.start_date) }} - {{ formatDate(event.end_date) }}</div>
              </div>
            </div>
            <p class="text-gray-700 mb-2">{{ event.description }}</p>
            <div class="mb-1">
              <span class="font-semibold text-xs text-pink-700">Type:</span>
              <span class="ml-1 text-gray-800">
                <template v-if="event.event_type === 'other'">
                  {{ event.event_type_description }}
                </template>
                <template v-else-if="event.event_type">
                  {{ event.event_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                </template>
                <template v-else>
                  N/A
                </template>
              </span>
            </div>
            <div v-if="event.venue_link" class="mb-2">
              <span class="font-semibold text-xs text-pink-700">Venue:</span>
              <a :href="event.venue_link" target="_blank" class="ml-1 text-blue-600 underline break-all">Google Maps</a>
            </div>
            
            <!-- Enrollment Information -->
            <div v-if="event.can_enroll !== undefined" class="mt-3 pt-3 border-t border-gray-200">
              <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500">
                  {{ event.enrollment_count || 0 }} enrolled
                </span>
                <div v-if="event.user_enrollment" class="text-xs px-2 py-1 rounded-full"
                     :class="{
                       'bg-green-100 text-green-700': event.user_enrollment.status === 'completed',
                       'bg-blue-100 text-blue-700': event.user_enrollment.status === 'enrolled',
                       'bg-gray-100 text-gray-700': event.user_enrollment.status === 'cancelled'
                     }">
                  {{ event.user_enrollment.status.charAt(0).toUpperCase() + event.user_enrollment.status.slice(1) }}
                </div>
              </div>
              
              <!-- Enrollment Actions -->
              <div v-if="event.can_enroll && !event.user_enrollment">
                <button @click="enrollInEvent(event.id)" 
                        :disabled="enrolling === event.id"
                        class="w-full bg-gradient-to-r from-green-500 to-blue-500 text-white px-3 py-2 rounded-full text-sm font-semibold hover:from-green-600 hover:to-blue-600 transition-all duration-200 disabled:opacity-50">
                  {{ enrolling === event.id ? 'Enrolling...' : 'Enroll Now' }}
                </button>
              </div>
              
              <div v-else-if="event.user_enrollment && event.user_enrollment.status === 'enrolled'">
                <button @click="cancelEnrollment(event.id)" 
                        :disabled="cancelling === event.id"
                        class="w-full bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-2 rounded-full text-sm font-semibold hover:from-red-600 hover:to-pink-600 transition-all duration-200 disabled:opacity-50">
                  {{ cancelling === event.id ? 'Cancelling...' : 'Cancel Enrollment' }}
                </button>
              </div>
              
              <div v-else-if="!event.can_enroll && !event.user_enrollment">
                <div class="text-xs text-gray-500 text-center">
                  <span v-if="!['seminars', 'workshops', 'contests'].includes(event.event_type)">
                    This event type doesn't allow enrollment
                  </span>
                  <span v-else-if="new Date(event.end_date) <= new Date()">
                    Event has ended
                  </span>
                  <span v-else>
                    Enrollment not available
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['clubId'],
  data() {
    return {
      events: [],
      loading: true,
      showEvents: false,
      enrolling: null,
      cancelling: null,
    };
  },
  mounted() {
    this.fetchEvents();
  },
  methods: {
    async fetchEvents() {
      this.loading = true;
      try {
        const response = await fetch(`/student/clubs/${this.clubId}/events`);
        this.events = await response.json();
      } catch (e) {
        this.events = [];
      }
      this.loading = false;
    },
    async enrollInEvent(eventId) {
      this.enrolling = eventId;
      try {
        const response = await fetch(`/events/${eventId}/enroll`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });
        
        const data = await response.json();
        
        if (response.ok) {
          // Refresh events to show updated enrollment status
          await this.fetchEvents();
          this.showSuccessMessage('Successfully enrolled in event!');
        } else {
          this.showErrorMessage(data.error || 'Failed to enroll in event');
        }
      } catch (error) {
        this.showErrorMessage('Network error occurred');
      } finally {
        this.enrolling = null;
      }
    },
    async cancelEnrollment(eventId) {
      this.cancelling = eventId;
      try {
        const response = await fetch(`/events/${eventId}/cancel`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });
        
        const data = await response.json();
        
        if (response.ok) {
          // Refresh events to show updated enrollment status
          await this.fetchEvents();
          this.showSuccessMessage('Successfully cancelled enrollment!');
        } else {
          this.showErrorMessage(data.error || 'Failed to cancel enrollment');
        }
      } catch (error) {
        this.showErrorMessage('Network error occurred');
      } finally {
        this.cancelling = null;
      }
    },
    formatDate(dateStr) {
      const d = new Date(dateStr);
      return d.toLocaleString();
    },
    showSuccessMessage(message) {
      // Simple alert for now - could be replaced with a toast notification
      alert(message);
    },
    showErrorMessage(message) {
      // Simple alert for now - could be replaced with a toast notification
      alert(message);
    },
  },
};
</script>

<style scoped>
.grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}
@media (min-width: 768px) {
  .grid {
    grid-template-columns: 1fr 1fr;
  }
}
</style>
