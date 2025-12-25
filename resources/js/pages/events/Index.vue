<script setup lang="ts">
import EventDialog from '@/components/events/EventDialog.vue';
import { Dialog } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { VueCal } from 'vue-cal';
import 'vue-cal/style';

const events = [
    {
        start: new Date(new Date().setHours(10, 30, 0, 0)),
        end: new Date(new Date().setHours(11, 30, 0, 0)),
        title: `Doctor Appt.`,
        content: '<i class="icon mdi mdi-stethoscope"></i>',
        contentFull:
            'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nihil inventore expedita veniam deleniti, labore corporis quas, aspernatur praesentium quia nisi, omnis quod autem.',
        class: 'health',
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
    selectedEvent.value = event;
    console.log(event);
    isDialogOpen.value = true;
}
</script>

<template>
    <Head title="Schedule" />

    <Dialog v-model:open="isDialogOpen">
        <EventDialog v-model:open="isDialogOpen" :event="selectedEvent" />
    </Dialog>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
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
