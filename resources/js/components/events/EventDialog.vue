<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
</script>

<template>
        <form>
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Edit event</DialogTitle>
                    <DialogDescription>
                        Make changes to your event here. Click save when
                        you're done.
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-4">
                    <div class="grid gap-3">
                        <Label for="start">Start</Label>
                        <Input
                            id="start"
                            name="start"
                            default-value= event.start
                        />
                    </div>
                    <div class="grid gap-3">
                        <Label for="end">End</Label>
                        <Input
                            id="end"
                            name="end"
                            default-value= event.end
                        />
                    </div>
                </div>
                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="outline"> Cancel </Button>
                    </DialogClose>
                    <Button type="submit"> Save changes </Button>
                </DialogFooter>
            </DialogContent>
        </form>
</template>
