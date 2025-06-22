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
              <div>
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
            <div v-if="event.venue_link" class="mb-1">
              <span class="font-semibold text-xs text-pink-700">Venue:</span>
              <a :href="event.venue_link" target="_blank" class="ml-1 text-blue-600 underline break-all">Google Maps</a>
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
    formatDate(dateStr) {
      const d = new Date(dateStr);
      return d.toLocaleString();
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
