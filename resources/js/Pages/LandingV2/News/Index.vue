<script setup lang="ts">
import LandingV2 from '@/Layouts/LandingV2.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import Pagination from '@/Components/customv2/Pagination.vue';
import type { PagePropsNews } from '../types';

const props = defineProps<PagePropsNews>();
const search = ref<string>(props.filters.search);

watch(search, debounce((value: string) => {
    router.get(route('landing.news'), { search: value }, {
        preserveState: true,
        replace: true
    })
}, 300));

function cropText(text: string, limit: number): string {
    if (text.length > limit) {
        return text.substring(0, limit) + '...';
    }
    return text;
}
</script>

<template>

    <Head>
        <title>{{ title }}</title>
        <meta name="description" content="Cari informasi berita kegiatan lengkap yang ada di Desa Kaputihan">
    </Head>

    <LandingV2>
        <section class="services__v3 section" id="home">
            <div class="container">
                <div class="row mb-5 mt-5">
                    <div class="col-md-8 mx-auto text-center">
                        <span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">
                            Berita
                        </span>
                        <h2 class="mb-3" data-aos="fade-up" data-aos-delay="100">
                            Baca Berita Desa Kaputihan
                        </h2>
                    </div>
                </div>
                <div class="row justify-content-center mb-5">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control form-control-lg" v-model="search"
                                autocomplete="off" placeholder="Cari berdasarkan judul berita...">
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6" v-for="news in newsList.data" :key="news.id">
                        <div class="card h-100">
                            <img :src="`/storage/foto-berita/${news.thumbnail}`" class="card-img-top"
                                :alt="`Foto ${news.judul}`">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ cropText(news.judul, 50) }}</h5>
                                <div class="card-text" v-html="cropText(news.content, 130)"></div>
                                <Link :href="route('landing.readNews', news.slug)" class="btn btn-primary mt-auto">Baca
                                Berita</Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5" v-if="newsList.data.length > 0">
                    <div class="col-12 d-flex justify-content-center">
                        <Pagination :links="newsList.links" />
                    </div>
                </div>
            </div>
        </section>
    </LandingV2>
</template>

<style scoped>
.card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>