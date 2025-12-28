<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { ActivityIcon, AlertTriangleIcon } from 'lucide-vue-next';

interface PotStat {
    total: number;
    remaining: number;
}

const props = defineProps<{
    stats: Record<string, PotStat>;
}>();
</script>

<template>
    <Alert class="bg-card bg-blue-50 border-slate-200">
        <component :is="ActivityIcon" class="h-4 w-4" />
        <AlertTitle class="mb-2 font-bold">Remaining Hours Overview</AlertTitle>
        <AlertDescription>
            <div class="flex flex-wrap gap-x-8 gap-y-4">
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
                            {{ Number(data.remaining) }}H
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
