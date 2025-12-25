<?php

require_once __DIR__ . '/core/BaseController.php';
require_once __DIR__ . '/php/City.php';
require_once __DIR__ . '/php/Package.php';

class CityController extends BaseController {
    private $cityModel;
    private $packageModel;

    public function __construct() {
        parent::__construct();
        $this->cityModel = new City();
        $this->packageModel = new Package();
    }

    public function show($id) {
        // المحاولة الأولى: جدول المدن
        $city = $this->cityModel->findById($id);

        if ($city) {
            $this->jsonResponse($city);
            return;
        }

        // fallback: بيانات packages
        $package = $this->packageModel->findById($id);

        if ($package) {
            $this->jsonResponse($package);
        } else {
            $this->jsonResponse(['error' => 'no id'], 404);
        }
    }

    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            $this->jsonResponse(['error' => 'Query parameter required'], 400);
            return;
        }

        // البحث في المدن أولاً
        $cities = $this->cityModel->search($query);
        
        // البحث في Packages أيضاً
        $packages = $this->packageModel->search($query);
        
        // دمج النتائج
        $results = array_merge($cities, $packages);
        
        $this->jsonResponse($results);
    }
}

