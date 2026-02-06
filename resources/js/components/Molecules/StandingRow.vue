<script lang="ts" setup>
import BaseBadge from '@/components/Atoms/BaseBadge.vue';
import type { Team } from '@/types';

defineProps<{
    team: Team;
    rank: number;
}>();

const gdBadgeVariant = (team: Team) => (team.gd > 0 ? 'success' : team.gd < 0 ? 'danger' : 'warning');
</script>

<template>
    <tr class="group border-b border-slate-100 transition-colors last:border-0 hover:bg-indigo-50/30">
        <td class="px-4 py-4 font-mono text-xs text-slate-500">{{ rank }}</td>
        <td class="px-2 py-4">
            <div class="font-semibold text-slate-800">{{ team.name }}</div>
        </td>
        <td class="px-2 py-4 text-center text-slate-600">{{ team.played }}</td>
        <td class="px-2 py-4 text-center text-slate-600">{{ team.won }}</td>
        <td class="px-2 py-4 text-center text-slate-600">{{ team.drawn }}</td>
        <td class="px-2 py-4 text-center text-slate-600">{{ team.lost }}</td>
        <td class="px-2 py-4 text-center font-mono">
            <BaseBadge :variant="gdBadgeVariant(team)"> {{ team.gd > 0 ? '+' : '' }}{{ team.gd }} </BaseBadge>
        </td>
        <td class="px-4 py-4 text-center font-bold text-indigo-700">{{ team.points }}</td>
    </tr>
</template>
