<script lang="ts" setup>
import BaseProgressBar from '@/components/Atoms/BaseProgressBar.vue';

defineProps({
    teamName: String,
    percentage: Number,
});
</script>

<template>
    <div class="space-y-2">
        <div class="flex justify-between text-sm">
            <span class="font-medium text-slate-700">{{ teamName }}</span>
            <span class="font-bold text-indigo-600">%{{ percentage }}</span>
        </div>
        <BaseProgressBar :value="percentage" />
    </div>
</template>
