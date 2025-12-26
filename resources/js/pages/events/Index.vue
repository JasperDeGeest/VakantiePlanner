<script setup lang="ts">
import EventDialog from '@/components/events/EventDialog.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { VueCal } from 'vue-cal';
import 'vue-cal/style';
import { Button } from '@/components/ui/button';

const events = [
    {
        title: 'Pot A: 3u',
        start: '2025-12-26 14:00',
        end: '2025-12-26 17:00',
        class: 'PotA',
    },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Schedule',
        href: '/schedule',
    },
];

// reactive state for dialog
const isDialogOpen = ref(false);
const selectedEvent = ref(null);

function handleEventClick(event: any) {
    selectedEvent.value = event.event;
    isDialogOpen.value = true;
}
</script>

<template>
    <Head title="Schedule" />
    <EventDialog v-model:open="isDialogOpen" :event="selectedEvent" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Button @click="isDialogOpen = true; selectedEvent=null;"> Create Event </Button>
            <vue-cal
                :events="events"
                events-on-month-view
                @event-click="handleEventClick"
                style="height: 100%"
                view="month"
                :views-bar="false"
                :time="false"
                week-numbers
                locale="en-us"
            />
        </div>
    </AppLayout>
</template>
