<template>
  <div>
    <button @click="showCreate = !showCreate" class="mb-4 px-6 py-2 bg-gradient-to-r from-pink-500 via-orange-400 to-yellow-400 text-white rounded-full shadow-lg hover:from-pink-600 hover:to-yellow-500 transition-all duration-200 flex items-center gap-2">
      <svg v-if="!showCreate" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      <span v-if="!showCreate">Create Event</span>
      <span v-else>Cancel</span>
    </button>
    <transition name="fade">
      <div v-if="showCreate" class="mb-6 p-6 bg-gradient-to-br from-yellow-50 via-pink-50 to-orange-50 rounded-xl shadow-xl border border-pink-100 animate-fade-in">
        <h3 class="font-semibold mb-2 text-pink-700 flex items-center gap-2"><svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> New Event</h3>
        <form @submit.prevent="createEvent" class="space-y-2">
          <input v-model="form.name" type="text" placeholder="Event Name" class="input" required />
          <textarea v-model="form.description" placeholder="Description" class="input" required></textarea>
          <input @change="onLogoChange" type="file" accept="image/jpeg" class="input mb-2" />
          <div class="flex gap-2">
            <input v-model="form.start_date" type="datetime-local" class="input flex-1" required />
            <input v-model="form.end_date" type="datetime-local" class="input flex-1" required />
          </div>
          <select v-model="form.event_type" class="input" required>
            <option value="" disabled>Select Event Type</option>
            <option value="workshops">Workshops</option>
            <option value="seminars">Seminars</option>
            <option value="contests">Contests</option>
            <option value="field_events">Field Events</option>
            <option value="other">Other</option>
          </select>
          <input v-if="form.event_type === 'other'" v-model="form.event_type_description" type="text" class="input" placeholder="Describe the event type" />
          <input v-model="form.venue_link" type="url" class="input" placeholder="Google Maps Link (optional)" />
          <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-green-400 via-lime-400 to-yellow-400 text-white rounded-full shadow hover:from-green-500 hover:to-yellow-500 transition-all duration-200">Create</button>
        </form>
      </div>
    </transition>
    <div v-if="loading" class="text-center py-8 animate-pulse text-pink-400 font-semibold text-lg flex flex-col items-center">
      <svg class="w-8 h-8 mb-2 animate-spin text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
      Loading events...
    </div>
    <div v-else>
      <div v-if="events.length === 0" class="text-gray-500 text-center py-12">
        <svg class="w-16 h-16 mx-auto mb-4 text-pink-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
        <span class="block text-lg font-semibold">No events found for this club.</span>
        <span class="text-sm text-gray-400">Start by creating your first event!</span>
      </div>
      <div v-else class="grid gap-8 md:grid-cols-2">
        <transition-group name="fade" tag="div">
          <div v-for="event in events" :key="event.id" class="bg-gradient-to-br from-yellow-50 via-pink-50 to-orange-50 rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-all duration-200 relative border-2 border-pink-200 animate-fade-in">
            <div class="flex items-center mb-3">
              <img v-if="event.logo" :src="`/storage/${event.logo}`" alt="Event Logo" class="h-14 w-14 rounded-full border-2 border-pink-200 shadow mr-4">
              <div v-else class="h-14 w-14 bg-pink-100 rounded-full flex items-center justify-center text-2xl text-pink-400 font-bold mr-4 shadow">
                {{ event.name.charAt(0).toUpperCase() }}
              </div>
              <div>
                <h3 class="text-xl font-bold text-pink-800 flex items-center gap-2">
                  {{ event.name }}
                  <span class="ml-2 px-2 py-0.5 bg-pink-100 text-pink-600 text-xs rounded-full">#{{ event.id }}</span>
                </h3>
                <div class="text-xs text-gray-400 mt-1 flex items-center gap-2">
                  <svg class="w-4 h-4 text-orange-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  {{ formatDate(event.start_date) }} - {{ formatDate(event.end_date) }}
                </div>
              </div>
            </div>
            <p class="text-gray-700 mb-4">{{ event.description }}</p>
            <div class="mb-2">
              <span class="font-semibold text-sm text-pink-700">Type:</span>
              <span class="ml-1 text-gray-800">
                <template v-if="event.event_type === 'other'">
                  {{ event.event_type_description }}
                </template>
                <template v-else-if="event.event_type">
                  {{ formatEventType(event.event_type) }}
                </template>
                <template v-else>
                  N/A
                </template>
              </span>
            </div>
            <div v-if="event.venue_link" class="mb-2">
              <span class="font-semibold text-sm text-pink-700">Venue:</span>
              <a :href="event.venue_link" target="_blank" class="ml-1 text-blue-600 underline break-all">Google Maps</a>
            </div>
            
            <!-- Enrollment Info -->
            <div v-if="['seminars', 'workshops', 'contests'].includes(event.event_type)" class="mb-3 p-2 bg-blue-50 rounded-lg border border-blue-100">
              <div class="flex items-center justify-between text-sm">
                <span class="font-semibold text-blue-700">📊 Enrollments:</span>
                <button @click="viewEnrollments(event.id)" class="text-blue-600 hover:underline font-medium">
                  {{ event.enrollment_count || 0 }} students enrolled
                </button>
              </div>
            </div>
            <div class="absolute top-4 right-4 flex gap-2">
              <button @click="editEvent(event)" class="px-3 py-1 bg-gradient-to-r from-pink-400 via-orange-400 to-yellow-400 text-white rounded-full shadow hover:from-pink-500 hover:to-yellow-500 transition-all duration-200 text-xs font-semibold">Edit</button>
              <button @click="deleteEvent(event.id)" class="px-3 py-1 bg-gradient-to-r from-red-400 via-pink-500 to-orange-400 text-white rounded-full shadow hover:from-red-500 hover:to-orange-500 transition-all duration-200 text-xs font-semibold">Delete</button>
            </div>
          </div>
        </transition-group>
      </div>
    </div>
    <transition name="fade">
      <div v-if="showEdit" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 animate-fade-in">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md border-2 border-pink-100">
          <h3 class="font-semibold mb-4 text-pink-700 flex items-center gap-2"><svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Edit Event</h3>
          <form @submit.prevent="updateEvent" class="space-y-2">
            <input v-model="editForm.name" type="text" placeholder="Event Name" class="input" required />
            <textarea v-model="editForm.description" placeholder="Description" class="input" required></textarea>
            <input v-model="editForm.logo" type="text" placeholder="Logo URL (optional)" class="input" />
            <input @change="onEditLogoChange" type="file" accept="image/jpeg" class="input mb-2" />
            <div class="flex gap-2">
              <input v-model="editForm.start_date" type="datetime-local" class="input flex-1" required />
              <input v-model="editForm.end_date" type="datetime-local" class="input flex-1" required />
            </div>
            <select v-model="editForm.event_type" class="input" required>
              <option value="" disabled>Select Event Type</option>
              <option value="workshops">Workshops</option>
              <option value="seminars">Seminars</option>
              <option value="contests">Contests</option>
              <option value="field_events">Field Events</option>
              <option value="other">Other</option>
            </select>
            <input v-if="editForm.event_type === 'other'" v-model="editForm.event_type_description" type="text" class="input" placeholder="Describe the event type" />
            <input v-model="editForm.venue_link" type="url" class="input" placeholder="Google Maps Link (optional)" />
            <div class="flex gap-2 mt-2">
              <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-pink-500 via-orange-400 to-yellow-400 text-white rounded-full shadow hover:from-pink-600 hover:to-yellow-500 transition-all duration-200">Update</button>
              <button type="button" @click="showEdit = false" class="w-full px-4 py-2 bg-gray-200 rounded-full">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: ['clubId'],
  data() {
    return {
      events: [],
      loading: true,
      showCreate: false,
      showEdit: false,
      form: {
        name: '',
        description: '',
        logo: '',
        start_date: '',
        end_date: '',
        event_type: '',
        event_type_description: '',
        venue_link: '',
      },
      editForm: {},
      editId: null,
      logoFile: null,
      editLogoFile: null,
    };
  },
  mounted() {
    this.fetchEvents();
  },
  methods: {
    async fetchEvents() {
      this.loading = true;
      const response = await fetch(`/club-manager/clubs/${this.clubId}/events/api`);
      this.events = await response.json();
      
      // Add enrollment count for each event
      for (let event of this.events) {
        if (['seminars', 'workshops', 'contests'].includes(event.event_type)) {
          try {
            const enrollmentResponse = await fetch(`/events/${event.id}/enrollments`);
            if (enrollmentResponse.ok) {
              const enrollments = await enrollmentResponse.json();
              event.enrollment_count = enrollments.length;
            } else {
              event.enrollment_count = 0;
            }
          } catch (e) {
            event.enrollment_count = 0;
          }
        }
      }
      
      console.log('Fetched events:', this.events);
      this.loading = false;
    },
    onLogoChange(e) {
      this.logoFile = e.target.files[0];
    },
    onEditLogoChange(e) {
      this.editLogoFile = e.target.files[0];
    },
    async createEvent() {
      const formData = new FormData();
      formData.append('name', this.form.name);
      formData.append('description', this.form.description);
      if (this.logoFile) {
        formData.append('logo', this.logoFile);
      }
      formData.append('start_date', this.form.start_date);
      formData.append('end_date', this.form.end_date);
      formData.append('event_type', this.form.event_type);
      formData.append('event_type_description', this.form.event_type === 'other' ? this.form.event_type_description : '');
      formData.append('venue_link', this.form.venue_link);
      const response = await fetch(`/club-manager/clubs/${this.clubId}/events`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: formData,
      });
      if (response.ok) {
        this.fetchEvents();
        this.showCreate = false;
        this.form = { name: '', description: '', logo: '', start_date: '', end_date: '', event_type: '', event_type_description: '', venue_link: '' };
        this.logoFile = null;
      }
    },
    editEvent(event) {
      this.editForm = { ...event };
      // Convert UTC dates to local datetime-local format
      if (event.start_date) {
        const startDate = new Date(event.start_date);
        this.editForm.start_date = this.formatDateForInput(startDate);
      }
      if (event.end_date) {
        const endDate = new Date(event.end_date);
        this.editForm.end_date = this.formatDateForInput(endDate);
      }
      this.editForm.event_type = event.event_type || '';
      this.editForm.event_type_description = event.event_type_description || '';
      this.editForm.venue_link = event.venue_link || '';
      this.editId = event.id;
      this.showEdit = true;
    },
    async updateEvent() {
      const formData = new FormData();
      formData.append('name', this.editForm.name);
      formData.append('description', this.editForm.description);
      if (this.editLogoFile) {
        formData.append('logo', this.editLogoFile);
      } else if (this.editForm.logo) {
        formData.append('logo', this.editForm.logo);
      }
      formData.append('start_date', this.editForm.start_date);
      formData.append('end_date', this.editForm.end_date);
      formData.append('event_type', this.editForm.event_type);
      formData.append('event_type_description', this.editForm.event_type === 'other' ? this.editForm.event_type_description : '');
      formData.append('venue_link', this.editForm.venue_link);
      const response = await fetch(`/club-manager/clubs/${this.clubId}/events/${this.editId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'X-HTTP-Method-Override': 'PUT' },
        body: formData,
      });
      if (response.ok) {
        this.fetchEvents();
        this.showEdit = false;
        this.editLogoFile = null;
      }
    },
    async deleteEvent(id) {
      if (!confirm('Delete this event?')) return;
      const response = await fetch(`/club-manager/clubs/${this.clubId}/events/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
      });
      if (response.ok) {
        this.fetchEvents();
      }
    },
    formatDate(dateStr) {
      const d = new Date(dateStr);
      return d.toLocaleString();
    },
    formatDateForInput(date) {
      // Format date for datetime-local input (YYYY-MM-DDTHH:MM)
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');
      return `${year}-${month}-${day}T${hours}:${minutes}`;
    },
    formatEventType(type) {
      if (!type) return 'N/A';
      return type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
    },
    viewEnrollments(eventId) {
      // Simple alert for now - could be replaced with a modal
      alert(`Viewing enrollments for event ID: ${eventId}. This feature will show enrolled students and allow marking as completed.`);
    },
  },
};
</script>

<style scoped>
.input {
  display: block;
  width: 100%;
  margin-bottom: 0.5rem;
  padding: 0.75rem;
  border: 1.5px solid #f9a8d4;
  border-radius: 0.75rem;
  background: #fff7ed;
  font-size: 1rem;
  transition: border 0.2s;
}
.input:focus {
  outline: none;
  border-color: #f472b6;
  background: #fff;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.animate-fade-in {
  animation: fadeIn 0.5s;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
