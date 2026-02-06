import { usePage } from '@inertiajs/vue3';
import type { ComputedRef, Ref } from 'vue';
import { inject, provide, computed, ref, watch } from 'vue';
import { calculateStandings } from '@/lib/standingCalculator';
import type { Fixture, PageProps, Prediction, Team } from '@/types';

const LeagueSymbol = Symbol('LeagueContext');

type Context = {
    teams: ComputedRef<Team[]>;
    fixtures: Ref<Fixture[]>; // Artık manipüle edilebilir bir Ref
    predictions: ComputedRef<Prediction[]>;
    standings: ComputedRef<Team[]>; // Anlık hesaplanan tablo
};

export const useLeagueContext = () => {
    const context = inject<Context>(LeagueSymbol);
    if (!context) throw new Error('useLeagueContext inject failed!');
    return context;
};

export const provideLeagueContext = () => {
    const page = usePage<PageProps>();

    const localFixtures = ref<Fixture[]>(
        JSON.parse(JSON.stringify(page.props.fixtures?.data || []))
    );

    watch(
        () => page.props.fixtures?.data,
        (newFixtures) => {
            localFixtures.value = JSON.parse(JSON.stringify(newFixtures || []));
        },
        { deep: true },
    );

    const teams = computed(() => page.props.teams?.data || []);
    const predictions = computed(() => page.props?.predictions || []);
    const standings = computed(() => calculateStandings(teams.value, localFixtures.value));

    provide<Context>(LeagueSymbol, {
        teams,
        fixtures: localFixtures, // Artık v-model ile değişen bu olacak
        predictions,
        standings, // StandingsTable.vue artık buna bakacak
    });
};
