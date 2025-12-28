<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, usePage } from '@inertiajs/vue3'; // Added usePage
import HoursController from '@/actions/App/Http/Controllers/HoursController';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Hours', href: '/hours' }];

// Get the user data from the global page props
const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <Head title="Hours" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Heading
                title="Total hours"
                description="Manage your total hours per type for the year."
            />

            <Form
                v-bind="HoursController.update.form()"
                class="space-y-6"
                v-slot="{ errors, processing, recentlySuccessful }"
            >
                <div class="grid gap-2">
                    <Label for="totalAHours">Pot A</Label>
                    <Input
                        id="totalAHours"
                        name="totalAHours"
                        type="number"
                        step="0.001"
                        :default-value="user.totalAHours"
                        required
                    />
                    <InputError :message="errors.totalAHours" />
                </div>

                <div class="grid gap-2">
                    <Label for="totalBHours">Pot B</Label>
                    <Input
                        id="totalBHours"
                        name="totalBHours"
                        type="number"
                        step="0.001"
                        :default-value="user.totalBHours"
                        required
                    />
                    <InputError :message="errors.totalBHours" />
                </div>

                <div class="grid gap-2">
                    <Label for="totalCHours">Pot C</Label>
                    <Input
                        id="totalCHours"
                        name="totalCHours"
                        type="number"
                        step="0.001"
                        :default-value="user.totalCHours"
                        required
                    />
                    <InputError :message="errors.totalCHours" />
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="processing">Save</Button>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p
                            v-show="recentlySuccessful"
                            class="text-sm text-neutral-600"
                        >
                            Saved.
                        </p>
                    </Transition>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
