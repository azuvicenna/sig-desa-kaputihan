<script setup lang="ts">
import App from '@/Layouts/App.vue'
import { Head, Link } from '@inertiajs/vue3'
import Button from '@/components/ui/button/Button.vue';
import EditNews from '@/Components/custom/news/EditNews.vue';
import { Card, CardContent } from '@/Components/ui/card';

defineProps<{
    title: string,
    news: any
}>()

</script>

<template>

    <Head :title="title" />
    <App>
        <template #header>
            <h2 class="text-3xl font-bold tracking-tight">
                {{ title }}
            </h2>
            <div class="flex items-center space-x-2">
                <Link :href="route('admin.news')">
                <Button class="shadcn-btn back-btn">Kembali</Button>
                </Link>
            </div>
        </template>
        <Card>
            <CardContent>
                <EditNews :data="news"/>
            </CardContent>
        </Card>
    </App>
</template>

<style scoped></style>