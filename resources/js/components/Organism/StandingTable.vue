<script lang="ts" setup>
import BaseCard from '@/components/Atoms/BaseCard.vue';
import SectionHeader from '@/components/Molecules/SectionHeader.vue';
import StandingRow from '@/components/Molecules/StandingRow.vue';
import { useLeagueContext } from '@/contexts/LeagueContext';

const { standings } = useLeagueContext();
</script>

<template>
    <BaseCard>
        <template #header>
            <SectionHeader title="League Standings" description="Current rank based on points, GD and goals." />
        </template>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-4 py-3 text-left text-[10px] font-bold tracking-wider text-slate-500 uppercase">Pos</th>
                        <th class="px-2 py-3 text-left text-[10px] font-bold tracking-wider text-slate-500 uppercase">Team</th>
                        <th class="px-2 py-3 text-center text-[10px] font-bold tracking-wider text-slate-500 uppercase">P</th>
                        <th class="px-2 py-3 text-center text-[10px] font-bold tracking-wider text-slate-500 uppercase">W</th>
                        <th class="px-2 py-3 text-center text-[10px] font-bold tracking-wider text-slate-500 uppercase">D</th>
                        <th class="px-2 py-3 text-center text-[10px] font-bold tracking-wider text-slate-500 uppercase">L</th>
                        <th class="px-2 py-3 text-center text-[10px] font-bold tracking-wider text-slate-500 uppercase">GD</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold tracking-wider text-slate-500 uppercase">Pts</th>
                    </tr>
                </thead>
                <tbody>
                    <StandingRow v-for="(team, index) in standings" :key="team.id" :team="team" :rank="index + 1" />
                </tbody>
            </table>
        </div>
    </BaseCard>
</template>
