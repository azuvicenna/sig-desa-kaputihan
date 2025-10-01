<script setup lang="ts">
import { onMounted, computed } from 'vue'
import L from 'leaflet'
import axios from 'axios'
import type { Feature, GeoJsonObject } from 'geojson';
import type { Location, GeoJSONProperties } from './types';

const props = withDefaults(defineProps < {
  height?: string
} > (), {
  height: '500'
});

const style = computed(() => ({
  height: `${props.height}px`
}));

onMounted(async () => {
  const map: L.Map = L.map('map', { scrollWheelZoom: false }).setView([-7.46706, 108.256], 15);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  try {
    const response = await fetch('/geojson/kaputihan.geojson');
    const data: GeoJsonObject = await response.json();

    L.geoJSON(data, {
      style: (feature?: Feature<any, GeoJSONProperties>) => {
        if (!feature) return {};
        const properties = feature.properties;

        if (properties.NAMOBJ === "Kaputihan") {
          return { color: "red", fillColor: "green" };
        } else if (properties.REMARK?.includes("Jalan")) {
          return { color: "#FFD700", weight: 2 };
        } else {
          return { color: "#F4A582", fillColor: "#F4A582" };
        }
      },
      onEachFeature: (feature: Feature<any, GeoJSONProperties>, layer: L.Layer) => {
        if (feature.properties && feature.properties.OBJECT) {
          layer.bindPopup(feature.properties.OBJECT);
        }
      }
    }).addTo(map);
  } catch (error) {
    console.error("Gagal memuat file GeoJSON:", error);
  }

  const getIcon = (color: string): L.Icon => {
    return L.icon({
      iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${color}.png`,
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    });
  };

  L.marker([-7.465070961927788, 108.24454005036246], { icon: getIcon('red') })
    .addTo(map)
    .bindPopup(`
      <b>Kantor Kepala Desa Kaputihan</b><br><br>
      <div class="d-flex justify-content-center">
        <a href="https://maps.google.com/?q=-7.465070961927788,108.24454005036246" target="_blank">
          <button class="btn btn-sm btn-primary">
            Buka di Google Maps
          </button>
        </a>
      </div>
    `)
    .openPopup();

  try {
    const response = await axios.get < Location[] > (route('admin.locationList'));

    response.data.forEach((lokasi: Location) => {
      L.marker([lokasi.latitude, lokasi.longitude], { icon: getIcon(lokasi.warna) })
        .addTo(map)
        .bindPopup(`
          <b>${lokasi.nama_lokasi}</b><br><br>
          <div class="d-flex justify-content-center">
            <a href="${lokasi.link}" target="_blank">
              <button class="btn btn-sm btn-primary">
                Buka di Google Maps
              </button>
            </a>
          </div>
        `);
    });
  } catch (error) {
    console.error("Gagal mengambil data lokasi:", error);
  }
});
</script>

<template>
  <div id="map" class="w-full z-10 rounded" :style="style"></div>
</template>

<style>
@import 'leaflet/dist/leaflet.css';

.leaflet-control-attribution {
  display: none;
}
</style>