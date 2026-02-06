<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import BaseButton from '@/components/Atoms/BaseButton.vue';
import ChampionshipPredictions from '@/components/Organism/ChampionshipPredictions.vue';
import StandingsTable from '@/components/Organism/StandingTable.vue';
import WeeklyFixtures from '@/components/Organism/WeeklyFixtures.vue';
import { useLeagueActions } from '@/composables/useLeagueActions';
import { provideLeagueContext } from '@/contexts/LeagueContext';

// Context'i bu seviyede başlatıyoruz, böylece Props Drilling önlenir ve tüm alt bileşenler inject edebilir.
provideLeagueContext();

const { generateFixtures, simulateAll } = useLeagueActions();
</script>

<template>
    <Head title="League Dashboard" />

    <div class="min-h-screen bg-slate-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">
            <header class="flex flex-col justify-between gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Champions League Simulator</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage fixtures, simulate matches and track championship probabilities.</p>
                </div>

                <div class="flex items-center gap-3">
                    <BaseButton @click="generateFixtures" variant="ghost"> Reset & Generate Fixtures </BaseButton>
                    <BaseButton @click="simulateAll" variant="primary"> Simulate All Weeks </BaseButton>
                </div>
            </header>

            <div class="grid grid-cols-12 items-start gap-8">
                <section class="col-span-12 space-y-8 lg:col-span-8">
                    <StandingsTable />
                </section>

                <aside class="col-span-12 lg:col-span-4">
                    <ChampionshipPredictions />
                </aside>

                <section class="col-span-12 pt-4">
                    <div class="mb-6 flex items-center gap-2">
                        <div class="h-1 w-8 rounded-full bg-indigo-600"></div>
                        <h2 class="text-xl font-bold text-slate-900">League Schedule</h2>
                    </div>
                    <WeeklyFixtures />
                </section>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Inertia Defer geçişleri için opsiyonel yumuşatma */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
