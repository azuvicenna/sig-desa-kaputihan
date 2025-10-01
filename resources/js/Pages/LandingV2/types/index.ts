export interface PageProps {
    title: string;
}

export interface PagePropsWithData extends PageProps {
    data: any;
}

export interface PagePropsBuilding extends PageProps {
    buildings: any;
    filters: any;
}

export interface PagePropsDetailBuilding extends PageProps {
    detailBuilding: any;
    buildingPhotos: any;
}
