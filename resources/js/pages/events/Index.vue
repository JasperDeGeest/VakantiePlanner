<script setup lang="ts">
import EventDialog from '@/components/events/EventDialog.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, CalenderEvent } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { VueCal } from 'vue-cal';
import 'vue-cal/style';
import '../../../css/events.css';
import { Button } from '@/components/ui/button';
import { index, update } from '@/routes/events';
import { store } from '@/actions/App/Http/Controllers/EventController';
import { Alert, AlertTitle } from '@/components/ui/alert';
import { CheckCircle2Icon } from 'lucide-vue-next';
import RemainingHours from '@/components/events/RemainingHours.vue';
import { useAppearance } from '@/composables/useAppearance';

const { appearance } = useAppearance();

const props = defineProps<{
    events: Array<CalenderEvent>;
    stats: Record<string, { total: number; remaining: number }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Schedule',
        href: index.url(),
    },
];

const form = useForm({
    class: '',
    start: '',
    end: '',
});

const isDialogOpen = ref(false);
const selectedEvent = ref<CalenderEvent | null>(null);

function handleEventClick(event: any) {
    selectedEvent.value = event.event;
    isDialogOpen.value = true;
}

function onSave(formData: any) {
    const action = selectedEvent.value?.id
        ? update({ event: selectedEvent.value.id })
        : store();

    form.class = formData.class;
    form.start = formData.start;
    form.end = formData.end;

    form.submit(action, {
        onSuccess: () => {
            isDialogOpen.value = false;
            selectedEvent.value = null;
            form.reset();
        },
        onError: (errors) => {
            console.error('Submission failed:', errors);
        },
    });
}
</script>

<template>
    <Head title="Schedule" />
    <EventDialog
        v-model:open="isDialogOpen"
        :event="selectedEvent"
        @save="onSave"
    />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <RemainingHours :stats="props.stats" />
            <Button
                @click="
                    isDialogOpen = true;
                    selectedEvent = null;
                "
            >
                Create Event
            </Button>
            <div v-if="$page.props.flash.success">
                <Alert class="bg-green-200">
                    <CheckCircle2Icon />
                    <AlertTitle>{{ $page.props.flash.success }}</AlertTitle>
                </Alert>
            </div>
            <vue-cal
                :events="props.events"
                events-on-month-view
                @event-click="handleEventClick"
                style="height: 100%"
                view="month"
                :views-bar="false"
                :time="false"
                week-numbers
                locale="en-us"
                :class="appearance === 'dark' ? 'vuecal--dark' : ''"
            />
        </div>
    </AppLayout>
</template>
