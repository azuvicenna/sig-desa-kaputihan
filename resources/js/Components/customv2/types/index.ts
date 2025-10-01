export interface Location {
    latitude: number;
    longitude: number;
    warna: string;
    nama_lokasi: string;
    link: string;
}

export interface GeoJSONProperties {
    NAMOBJ: string;
    REMARK?: string;
    OBJECT?: string;
}

export interface PagePropsSchedule {
  endpoint?: string; 
  start?: string;
  center?: string;
  end?: string;
}
