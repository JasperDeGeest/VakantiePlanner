<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import {
    ActivityIcon,
    AlertTriangleIcon,
    ArrowRightIcon,
} from 'lucide-vue-next';
// Import your generated Wayfinder action/route
import HoursController from '@/actions/App/Http/Controllers/HoursController';

interface PotStat {
    total: number | null;
    remaining: number;
}

const props = defineProps<{
    stats: Record<string, PotStat>;
}>();

const allHoursAreNull = computed(() => {
    return Object.values(props.stats).every(
        (stat) => stat.total === null || stat.total === 0,
    );
});
</script>

<template>
    <Alert class="border-slate-200 bg-blue-50 dark:border-slate-700 dark:bg-blue-950">
        <component
            :is="allHoursAreNull ? AlertTriangleIcon : ActivityIcon"
            class="h-4 w-4"
        />

        <div class="flex w-full items-center justify-between">
            <AlertTitle class="mb-2 font-bold"
                >Remaining Hours Overview</AlertTitle
            >

            <Link
                v-if="allHoursAreNull"
                :href="HoursController.index.url()"
                class="flex items-center gap-1 text-xs font-semibold text-blue-600 transition-colors hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
            >
                Set annual hours <ArrowRightIcon class="h-3 w-3" />
            </Link>
        </div>

        <AlertDescription>
            <div v-if="allHoursAreNull" class="text-sm text-slate-600 italic dark:text-slate-400">
                Your annual hour pots haven't been set for the current year.
            </div>

            <div v-else class="flex flex-wrap gap-x-8 gap-y-4">
                <div
                    v-for="(data, label) in stats"
                    :key="label"
                    class="flex flex-col border-l-2 border-primary/20 pl-3"
                >
                    <span class="text-xs font-medium text-foreground uppercase">
                        {{ label }}
                    </span>
                    <div class="flex items-baseline gap-1">
                        <span
                            class="text-xl font-bold text-foreground tabular-nums"
                        >
                            {{ Number(data.remaining).toFixed(2) }}H
                        </span>
                        <span class="text-[10px] text-foreground">
                            / {{ data.total }}H
                        </span>
                    </div>
                </div>
            </div>
        </AlertDescription>
    </Alert>
</template>
