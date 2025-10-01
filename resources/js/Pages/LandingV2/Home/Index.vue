<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LandingV2 from '@/Layouts/LandingV2.vue';
import Hero from '@/Pages/LandingV2/Home/section/Hero.vue';
import About from '@/Pages/LandingV2/Home/section/About.vue';
import OrganizationStructure from '@/Pages/LandingV2/Home/section/OrganizationStructure.vue';
import NatureCondition from '@/Pages/LandingV2/Home/section/NatureCondition.vue';
import CountVillage from '@/Pages/LandingV2/Home/section/CountVillage.vue';
import BuildingCategory from '@/Pages/LandingV2/Home/section/BuildingCategory.vue';
import FAQ from '@/Pages/LandingV2/Home/section/FAQ.vue';
import Report from '@/Pages/LandingV2/Home/section/Report.vue';
import Sponsors from '@/Pages/LandingV2/Home/section/Sponsors.vue';
import type { PagePropsWithData } from '../types';

const props = defineProps<PagePropsWithData>();

function pickFromObject<T extends object, K extends keyof T>(sourceObject: T, keys: K[]): Pick<T, K> {
  return keys.reduce((newObj, key) => {
    if (sourceObject && Object.prototype.hasOwnProperty.call(sourceObject, key)) {
      newObj[key] = sourceObject[key];
    }
    return newObj;
  }, {} as Pick<T, K>);
}

const orgKeys: string[] = ['jumlah_penduduk', 'jumlah_dusun', 'jumlah_rt', 'jumlah_rw'];
const profileKeys: string[] = ['suhu_rata_rata', 'lokasi_elevasi', 'luas_wilayah', 'alamat_desa'];

const countOrg = pickFromObject(props.data.villageProfile, orgKeys);
const villageProfile = pickFromObject(props.data.villageProfile, profileKeys);
</script>

<template>

  <Head>
    <title>{{ title }}</title>
    <meta name="description" content="Temukan data geografis dan peta interaktif Desa Kaputihan dalam satu platform.">
  </Head>

  <LandingV2>
    <Hero />
    <About :dataProps="data.setting" />
    <OrganizationStructure :organization="data.setting.foto_organisasi" />
    <NatureCondition :dataProps="villageProfile" />
    <CountVillage :dataProps="countOrg" />
    <BuildingCategory />
    <FAQ />
    <Report />
    <Sponsors />
  </LandingV2>
</template>