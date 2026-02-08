<script setup lang="ts">
import EventDialog from '@/components/events/EventDialog.vue';
import RemainingHours from '@/components/events/RemainingHours.vue';
import { Alert, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { useAppearance } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import { destroy, index, store, update } from '@/routes/events';
import { type BreadcrumbItem, CalenderEvent } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { CheckCircle2Icon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { VueCal } from 'vue-cal';
import 'vue-cal/style';
import '../../../css/events.css';

const { isDark } = useAppearance();

const props = defineProps<{
    events: Array<CalenderEvent>;
    stats: Record<string, { total: number; remaining: number }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Schema',
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


function openNewEvent() {
    form.clearErrors();
    selectedEvent.value = null;
    isDialogOpen.value = true;
}

function handleEventClick(event: any) {
    form.clearErrors();
    selectedEvent.value = event.event;
    isDialogOpen.value = true;
}

watch(isDialogOpen, (open) => {
    if (!open) {
        form.clearErrors();
    }
});


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
    });
}

function onDelete(id: number) {
    form.delete(destroy({ event: id }).url, {
        onSuccess: () => {
            isDialogOpen.value = false;
            selectedEvent.value = null;
        },
    });
}
</script>

<template>
    <Head title="Schema" />

    <EventDialog
        v-model:open="isDialogOpen"
        :event="selectedEvent"
        :errors="form.errors"
        @save="onSave"
        @delete="onDelete"
    />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <RemainingHours :stats="props.stats" />

            <Button @click="openNewEvent"> Evenement aanmaken </Button>

            <div v-if="$page.props.flash.success">
                <Alert class="bg-green-200 dark:bg-green-900">
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
                locale="nl"
                :class="isDark ? 'vuecal--dark' : ''"
            />
        </div>
    </AppLayout>
</template>
