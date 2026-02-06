<script lang="ts" setup>
import { computed } from 'vue';
import BaseButton from '@/components/Atoms/BaseButton.vue';
import BaseCard from '@/components/Atoms/BaseCard.vue';
import MatchResult from '@/components/Molecules/MatchResult.vue';
import SectionHeader from '@/components/Molecules/SectionHeader.vue';
import { useLeagueActions } from '@/composables/useLeagueActions';
import { useLeagueContext } from '@/contexts/LeagueContext';
import type { Fixture } from '@/types';

const { fixtures } = useLeagueContext();
const { simulateWeek } = useLeagueActions();

// Match Group by week
const groupedFixtures = computed(() => {
    return Object.groupBy(fixtures.value, (item) => item.week);
});

const currentWeek = computed(() => {
    const unplayed = fixtures.value.find((f) => !f.is_played);
    return unplayed ? unplayed.week : null;
});

const updateHomeScore = (match: Fixture, score?: number | null) => {
    match.home_score = Number.isFinite(score) ? Number(score) : null;
};

const updateAwayScore = (match: Fixture, score?: number | null) => {
    match.away_score = Number.isFinite(score) ? Number(score) : null;
};
</script>

<template>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <BaseCard
            v-for="(matches, week) in groupedFixtures"
            :key="week"
            :class="{ 'ring-2 ring-indigo-500 ring-offset-2': Number(week) === currentWeek }"
        >
            <template #header>
                <SectionHeader :title="`Week ${week}`">
                    <template #actions>
                        <BaseButton v-if="Number(week) === currentWeek" @click="simulateWeek(week)" variant="success"> Play Week </BaseButton>
                    </template>
                </SectionHeader>
            </template>

            <div class="space-y-4">
                <template v-for="match in matches" :key="match.id">
                    <MatchResult
                        :home-team-name="match.home_team?.name || 'TBD'"
                        :away-team-name="match.away_team?.name || 'TBD'"
                        :home-score="match.home_score as number"
                        :away-score="match.away_score as number"
                        @update:home-score="updateHomeScore(match, $event)"
                        @update:away-score="updateAwayScore(match, $event)"
                        :is-played="match.is_played"
                    />
                </template>
            </div>
        </BaseCard>
    </div>
</template>
