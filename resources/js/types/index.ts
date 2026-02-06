export * from './auth';

import type { Auth } from './auth';

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    auth: Auth;
    [key: string]: unknown;
};

export interface CollectionResource<T> {
    data: T[];
}

/**
 * Takım temel verileri (TeamResource karşılığı)
 */
export interface Team {
    id: number;
    name: string;
    logo?: string;
    points: number;
    played: number;
    won: number;
    drawn: number;
    lost: number;
    gf: number;
    ga: number;
    gd: number;
}

/**
 * Fikstür ve Maç verileri (GameResource karşılığı)
 */
export interface Fixture {
    id: number;
    week: number;
    home_team_id: number;
    away_team_id: number;
    home_score: number | null;
    away_score: number | null;
    is_played: boolean;
    // İlişkisel datalar (Eğer Resource içinde yüklüyorsan)
    home_team?: Team;
    away_team?: Team;
}

/**
 * Tahminler veya Olasılıklar için ValueObject yapısı
 */
export interface Prediction {
    team_id: number;
    team_name: string;
    championship_probability: number; // Örn: 0.45 (%45)
}

/**
 * Inertia Page Props (Backend'den gelen ana veri yapısı)
 */
export type PageProps = AppPageProps<{
    // Lig ekranı için gerekli ana state'ler
    teams: CollectionResource<Team>;
    fixtures: CollectionResource<Fixture>;
    predictions: Prediction[];
    current_week: number;
    total_weeks: number;
    flash: {
        message: string | null;
        error: string | null;
    };
}>;
