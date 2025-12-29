<script setup lang="ts">
import {
    AlertTriangle,
    Clock,
    Trash2,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { formatForPicker } from '@/composables/useDateFormat';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    open: boolean;
    event: any | null;
}>();

const emit = defineEmits(['update:open', 'save', 'delete']);

const isConfirmingDelete = ref(false);
const form = ref({
    class: '',
    date: '', // YYYY-MM-DD
    startTime: '', // HH:mm
    endTime: '', // HH:mm
});


const timeOptions = computed(() => {
    const times = [];
    for (let h = 0; h < 24; h++) {
        for (let m = 0; m < 60; m += 15) {
            const hh = h.toString().padStart(2, '0');
            const mm = m.toString().padStart(2, '0');
            times.push(`${hh}:${mm}`);
        }
    }
    return times;
});

watch(
    () => props.open,
    (isOpen) => {
        isConfirmingDelete.value = false;
        if (isOpen) {
            if (props.event) {
                const fullStart = formatForPicker(props.event.start);
                const fullEnd = formatForPicker(props.event.end);

                form.value = {
                    class: props.event.class,
                    date: fullStart.split('T')[0],
                    startTime: fullStart.split('T')[1].substring(0, 5),
                    endTime: fullEnd.split('T')[1].substring(0, 5),
                };
            } else {
                form.value = {
                    class: '',
                    date: '',
                    startTime: '',
                    endTime: '',
                };
            }
        }
    },
    { immediate: true },
);

const handleSave = () => {
    const payload = {
        class: form.value.class,
        start: `${form.value.date}T${form.value.startTime}`,
        end: `${form.value.date}T${form.value.endTime}`,
    };

    emit('save', payload);
    emit('update:open', false);
};

const handleDelete = () => {
    if (!isConfirmingDelete.value) {
        isConfirmingDelete.value = true;
    } else {
        emit('delete', props.event.id);
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-[425px]">
            <form @submit.prevent="handleSave">
                <DialogHeader>
                    <DialogTitle>{{
                        event ? 'Edit Event' : 'Create Event'
                    }}</DialogTitle>
                    <DialogDescription>
                        Pick a day and select the 15-minute time intervals.
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <Label for="class">Category</Label>
                        <Select v-model="form.class">
                            <SelectTrigger id="class">
                                <SelectValue placeholder="Select a category" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="PotA">Pot A</SelectItem>
                                <SelectItem value="PotB">Pot B</SelectItem>
                                <SelectItem value="PotC">Pot C</SelectItem>
                                <SelectItem value="Ziek">Ziek</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="grid gap-2">
                        <Label for="date">Date</Label>
                        <Input
                            id="date"
                            type="date"
                            v-model="form.date"
                            required
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label>Start Time</Label>
                            <Select v-model="form.startTime">
                                <SelectTrigger>
                                    <Clock
                                        class="mr-2 h-4 w-4 text-muted-foreground"
                                    />
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="t in timeOptions"
                                        :key="t"
                                        :value="t"
                                    >
                                        {{ t }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="grid gap-2">
                            <Label>End Time</Label>
                            <Select v-model="form.endTime">
                                <SelectTrigger>
                                    <Clock
                                        class="mr-2 h-4 w-4 text-muted-foreground"
                                    />
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="t in timeOptions"
                                        :key="t"
                                        :value="t"
                                    >
                                        {{ t }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </div>

                <DialogFooter
                    class="mt-4 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between"
                >
                    <div v-if="event">
                        <Button
                            type="button"
                            :variant="
                                isConfirmingDelete ? 'destructive' : 'ghost'
                            "
                            class="w-full transition-all duration-200 sm:w-auto"
                            @click="handleDelete"
                        >
                            <component
                                :is="
                                    isConfirmingDelete ? AlertTriangle : Trash2
                                "
                                class="mr-2 h-4 w-4"
                            />
                            {{
                                isConfirmingDelete
                                    ? 'Confirm Delete?'
                                    : 'Delete'
                            }}
                        </Button>
                    </div>
                    <div v-else aria-hidden="true" />
                    <div class="flex flex-col-reverse gap-2 sm:flex-row">
                        <Button
                            variant="outline"
                            type="button"
                            @click="emit('update:open', false)"
                        >
                            Cancel
                        </Button>
                        <Button type="submit" :disabled="isConfirmingDelete">
                            {{ event ? 'Save Changes' : 'Create Event' }}
                        </Button>
                    </div>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
