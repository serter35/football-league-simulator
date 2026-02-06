import { router } from '@inertiajs/vue3';
import { generate, simulateNextWeek, simulateAll as simulateAllAction } from 'App/Http/Controllers/LeagueController';

export function useLeagueActions() {
    const generateFixtures = () => {
        router.post(
            generate(),
            {},
            {
                preserveScroll: true,
                onSuccess: () => console.log('Fixtures Generated'),
            },
        );
    };

    const simulateWeek = (week: number) => {
        const only = ['fixtures', 'teams', 'current_week'];

        if (week >= 4) {
            only.push('predictions');
        }

        router.post(
            simulateNextWeek(week),
            {},
            {
                preserveScroll: true,
                only,
            },
        );
    };

    const simulateAll = () => {
        router.post(
            simulateAllAction(),
            {},
            {
                preserveScroll: true,
            },
        );
    };

    return {
        generateFixtures,
        simulateWeek,
        simulateAll,
    };
}
