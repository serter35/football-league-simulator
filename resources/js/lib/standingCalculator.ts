import type { Fixture, Team } from '@/types';

/**
 * Maç sonuçlarına göre puan durumunu anlık olarak hesaplar.
 */
export const calculateStandings = (teams: Team[], fixtures: Fixture[]): Team[] => {
    const stats = teams.reduce((acc, team) => {
        acc[team.id] = {
            ...team,
            played: 0, won: 0, drawn: 0, lost: 0, gf: 0, ga: 0, gd: 0, points: 0
        };
        return acc;
    }, {} as Record<number, any>);

    fixtures.forEach((match) => {
        const hScore = match.home_score;
        const aScore = match.away_score;

        // Skorlar eksikse hesaba katma
        if (hScore === null || aScore === null || hScore === undefined || aScore === undefined) return;

        const hId = match.home_team_id || match.home_team?.id;
        const aId = match.away_team_id || match.away_team?.id;

        const home = stats[hId!];
        const away = stats[aId!];

        if (!home || !away) return;

        home.played++; away.played++;
        home.gf += Number(hScore); home.ga += Number(aScore);
        away.gf += Number(aScore); away.ga += Number(hScore);

        if (Number(hScore) > Number(aScore)) {
            home.won++; home.points += 3;
            away.lost++;
        } else if (Number(hScore) < Number(aScore)) {
            away.won++; away.points += 3;
            home.lost++;
        } else {
            home.drawn++; away.drawn++;
            home.points += 1; away.points += 1;
        }

        home.gd = home.gf - home.ga;
        away.gd = away.gf - away.ga;
    });

    return Object.values(stats).sort((a, b) =>
        b.points - a.points || b.gd - a.gd || b.gf - a.gf
    );
};
