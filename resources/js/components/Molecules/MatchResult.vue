<script lang="ts" setup>
import BaseInput from '@/components/Atoms/BaseInput.vue';

// Çift yönlü bağlama: [homeScore, awayScore]
const homeScore = defineModel('homeScore', { type: Number });
const awayScore = defineModel('awayScore', { type: Number });

defineProps({
    homeTeamName: String,
    awayTeamName: String,
    isPlayed: Boolean,
});
</script>

<template>
    <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-100 bg-slate-50 p-3">
        <div class="flex-1 text-right font-medium text-slate-700">{{ homeTeamName }}</div>

        <div class="flex w-32 items-center gap-2">
            <BaseInput v-model="homeScore" type="number" min="0" :disabled="isPlayed" />
            <span class="font-bold text-slate-400">-</span>
            <BaseInput v-model="awayScore" type="number" min="0" :disabled="isPlayed" />
        </div>

        <div class="flex-1 text-left font-medium text-slate-700">{{ awayTeamName }}</div>
    </div>
</template>
