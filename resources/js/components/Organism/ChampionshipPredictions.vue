<script lang="ts" setup>
import BaseCard from '@/components/Atoms/BaseCard.vue';
import PredictionItem from '@/components/Molecules/PredictionItem.vue';
import SectionHeader from '@/components/Molecules/SectionHeader.vue';
import { useLeagueContext } from '@/contexts/LeagueContext';

const { predictions } = useLeagueContext();
</script>

<template>
    <BaseCard>
        <template #header>
            <SectionHeader title="Championship Predictions" />
        </template>

        <div v-if="predictions.length > 0" class="space-y-6">
            <PredictionItem
                v-for="prediction in predictions"
                :key="prediction.team_id"
                :team-name="prediction.team_name"
                :percentage="prediction.percentage"
            />
        </div>

        <div v-else class="flex flex-col items-center justify-center py-8 text-center">
            <div class="mb-3 rounded-full bg-slate-50 p-3">
                <span class="text-2xl">📊</span>
            </div>
            <p class="text-sm text-slate-500">
                Predictions will be available <br />
                <span class="font-bold text-indigo-600">after Week 4</span>
            </p>
        </div>
    </BaseCard>
</template>
