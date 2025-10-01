export interface Properties {
    dataProps: any
}

export interface Category {
    id: number;
    nama_kategori: string;
    icon: string;
    deskripsi: string;
    buildings_count: number | string;
}

export interface ApiResponse {
    data: Category[];
    next_page_url: string | null;
}