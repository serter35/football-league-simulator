<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div v-if="$slots.header" class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <slot name="header" />
        </div>
        <div class="p-6">
            <slot />
        </div>
    </div>
</template>
