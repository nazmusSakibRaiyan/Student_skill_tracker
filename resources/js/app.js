import './bootstrap';
import { createApp } from 'vue';
import ClubEvents from './components/ClubEvents.vue';
import ClubManagerEvents from './components/ClubManagerEvents.vue';

const el = document.getElementById('club-events');
if (el) {
    createApp(ClubEvents, { clubId: el.getAttribute('data-club-id') }).mount('#club-events');
}

const el2 = document.getElementById('club-manager-events');
if (el2) {
    createApp(ClubManagerEvents, { clubId: el2.getAttribute('data-club-id') }).mount('#club-manager-events');
}
