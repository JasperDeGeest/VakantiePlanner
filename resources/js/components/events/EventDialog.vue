<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { formatForPicker } from '@/composables/useDateFormat';
import { VueDatePicker } from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { nl } from 'date-fns/locale';
import { AlertTriangle, Trash2 } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useAppearance } from '@/composables/useAppearance';

const props = defineProps<{
    open: boolean;
    event: any | null;
}>();

const emit = defineEmits(['update:open', 'save', 'delete']);

const { isDark } = useAppearance();

const isConfirmingDelete = ref(false);
const form = ref({
    class: '',
    date: '', // YYYY-MM-DD
    startTime: '', // HH:mm
    endTime: '', // HH:mm
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
        <DialogContent>
            <form @submit.prevent="handleSave">
                <DialogHeader>
                    <DialogTitle>{{
                        event ? 'Evenement bewerken' : 'Evenement aanmaken'
                    }}</DialogTitle>
                    <DialogDescription>
                        Kies een dag en selecteer de 15-minuten
                        tijdsintervallen.
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <Label for="class">Categorie</Label>
                        <Select v-model="form.class" required>
                            <SelectTrigger id="class">
                                <SelectValue
                                    placeholder="Selecteer een categorie"
                                />
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
                        <Label for="date">Datum</Label>
                        <VueDatePicker
                            id="date"
                            v-model="form.date"
                            :locale="nl"
                            :model-type="'yyyy-MM-dd'"
                            :time-config="{ enableTimePicker: false }"
                            :input-attrs="{ required: true }"
                            :dark="isDark"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label>Starttijd</Label>
                            <VueDatePicker
                                v-model="form.startTime"
                                :locale="nl"
                                :model-type="'HH:mm'"
                                :time-picker="true"
                                :input-attrs="{ required: true }"
                                :time-config="{ minutesIncrement: 15 }"
                                :dark="isDark"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label>Eindtijd</Label>
                            <VueDatePicker
                                v-model="form.endTime"
                                :locale="nl"
                                :model-type="'HH:mm'"
                                :time-picker="true"
                                :input-attrs="{ required: true }"
                                :time-config="{ minutesIncrement: 15 }"
                                :dark="isDark"
                            />
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
                                    ? 'Verwijderen bevestigen?'
                                    : 'Verwijderen'
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
                            Annuleren
                        </Button>
                        <Button type="submit" :disabled="isConfirmingDelete">
                            {{
                                event
                                    ? 'Wijzigingen opslaan'
                                    : 'Evenement aanmaken'
                            }}
                        </Button>
                    </div>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
