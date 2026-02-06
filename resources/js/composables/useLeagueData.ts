import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { PageProps, Fixture } from '@/types';

export function useLeagueData() {
    const page = usePage<PageProps>();

    // Computed değerler sayesinde tipler otomatik çözülür
    const fixtures = computed(() => page.props.fixtures.data);
    const teams = computed(() => page.props.teams.data);

    const getFixturesByWeek = (week: number): Fixture[] => {
        return fixtures.value.filter((f) => f.week === week);
    };

    const currentWeek = computed(() => {
        const nextGame = page.props.fixtures.data.find((f) => !f.is_played);
        return nextGame ? nextGame.week : 1;
    });

    return {
        fixtures,
        teams,
        getFixturesByWeek,
        currentWeek,
    };
}
