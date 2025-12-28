<script setup lang="ts">
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { formatForPicker } from '@/composables/useDateFormat';

const props = defineProps<{
    open: boolean;
    event: any | null;
}>();

const emit = defineEmits(['update:open', 'save']);

const form = ref({
    class: '',
    start: '',
    end: '',
});

watch(
    () => props.open,
    (isOpen) => {
        if (isOpen) {
            if (props.event) {
                // edit
                form.value = {
                    class: props.event.class,
                    start: formatForPicker(props.event.start),
                    end: formatForPicker(props.event.end),
                };
            } else {
                // create
                form.value = {
                    class: '',
                    start: '',
                    end: '',
                };
            }
        }
    },
);

const handleSave = () => {
    // TODO: Add validation as needed
    //callback naar de parent
    emit('save', { ...form.value });
    emit('update:open', false);
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
                        {{
                            event
                                ? 'Modify the details below.'
                                : 'Add a new event to your schedule.'
                        }}
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div class="grid gap-3">
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

                    <div class="grid gap-3">
                        <Label for="start">Start</Label>
                        <Input
                            id="start"
                            type="datetime-local"
                            v-model="form.start"
                        />
                    </div>

                    <div class="grid gap-3">
                        <Label for="end">End</Label>
                        <Input
                            id="end"
                            type="datetime-local"
                            v-model="form.end"
                        />
                    </div>
                </div>

                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline">Cancel</Button>
                    </DialogClose>
                    <Button type="submit">
                        {{ event ? 'Save Changes' : 'Create Event' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
