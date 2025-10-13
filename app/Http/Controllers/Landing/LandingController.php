<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Services\BuildingService;
use App\Services\LandingService;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    protected $landingService;
    protected $buildingService;
    protected $newsService;

    public function __construct(LandingService $landingService, BuildingService $buildingService, NewsService $newsService)
    {
        $this->landingService = $landingService;
        $this->buildingService = $buildingService;
        $this->newsService = $newsService;
    }

    public function index()
    {
        $title = 'Selamat Datang di Sistem Infromasi Geografis Desa Kaputihan';

        $data = [
            'villageProfile' => $this->landingService->getVillageProfile(),
            'setting' => $this->landingService->getSetting(),
        ];

        return Inertia::render('LandingV2/Home/Index', compact('title', 'data'));
    }

    public function territoryBoundary()
    {
        $title = 'Peta batas-batas wilayah di Desa Kaputihan';

        return Inertia::render('LandingV2/TerritoryBoundary/Index', compact('title'));
    }

    public function statisticVillage()
    {
        $title = 'Statistik Desa';

        return Inertia::render('LandingV2/StatisticVillage/Index', compact('title'));
    }

    public function buildings(Request $request, $id = null)
    {
        $title = 'Bangunan-bangunan di Desa Kaputihan';
        $search = $request->input('search');
        $buildings = $this->buildingService->paginatedResult($id, $search);
        $filters = $request->only('search');

        return Inertia::render('LandingV2/Buildings/Index', compact('title', 'buildings', 'filters'));
    }

    public function news(Request $request)
    {
        $title = 'Baca berita Desa Kaputihan';
        $search = $request->input('search');
        $newsList = $this->newsService->paginatedResult($search);
        $filters = $request->only('search');

        return Inertia::render('LandingV2/News/Index', compact('title', 'newsList', 'filters'));
    }

    public function detailBuilding(string $slug)
    {
        $building = $this->buildingService->buildingData($slug);
        $detailBuilding = $building['building'];
        $buildingPhotos = $building['photos'];

        $title = 'Detail ' . $detailBuilding->category->nama_kategori . ': ' . $detailBuilding->nama_bangunan;

        return Inertia::render('LandingV2/Buildings/Detail', compact('title', 'detailBuilding', 'buildingPhotos'));
    }

    public function schedule()
    {
        $title = 'Jadwal Kegiatan Desa';

        return Inertia::render('LandingV2/Schedule/Index', compact('title'));
    }
}
