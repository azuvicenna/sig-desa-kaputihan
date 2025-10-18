<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LandingV2 from '@/Layouts/LandingV2.vue';
import type { PagePropsWithData } from '../types';

defineProps<PagePropsWithData>();

function formatTanggal(tanggal: string) {
    if (!tanggal) return '-'
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(tanggal))
}
</script>

<template>

    <Head>
        <title>{{ title }}</title>
        <meta name="description" :content="`Baca artikel lengkap tentang ${data.judul}.`">
    </Head>

    <LandingV2>
        <section class="services__v3 section" id="home">
            <div class="container">
                <article class="mt-5">
                    <h1 class="mb-4">{{ data.judul }}</h1>

                    <div>
                        <img :src="`/storage/foto-berita/${data.thumbnail}`" :alt="data.judul" class="img-fluid rounded"
                            style="width: 100%; height: auto;" loading="lazy">
                    </div>

                    <div class="d-flex align-items-center my-4">
                        <img src="/placeholder/profile.png" :alt="data.user.nama" class="rounded-circle"
                            style="width: 40px; height: 40px; margin-right: 15px;">
                        <div>
                            <div class="fw-bold">{{ data.user.nama }}</div>
                            <div class="text-muted" style="font-size: 0.9em;">{{ formatTanggal(data.created_at) }}</div>
                        </div>
                    </div>

                    <div v-html="data.content" class="fs-5" style="line-height: 1.7;"></div>

                    <div v-if="data.newsPhotos && data.newsPhotos.length > 0" class="mt-5">
                        <h3 class="mb-4">Galeri Foto</h3>
                        <div class="row">
                            <div v-for="photo in data.newsPhotos" :key="photo.id" class="col-lg-4 col-md-6 mb-4">
                                <a :href="photo.url" data-lightbox="news-gallery" :data-title="photo.caption">
                                    <img :src="photo.image_name" alt="Foto dokumentasi"
                                        class="img-fluid rounded shadow-sm"
                                        style="width: 100%; height: 250px; object-fit: cover;">
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </LandingV2>
</template>