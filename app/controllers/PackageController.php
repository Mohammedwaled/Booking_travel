<?php

require_once __DIR__ . '/core/BaseController.php';
require_once __DIR__ . '/php/Package.php';

class PackageController extends BaseController {
    private $packageModel;

    public function __construct() {
        parent::__construct();
        $this->packageModel = new Package();
    }

    public function index() {
        $packages = $this->packageModel->findApproved();
        $this->jsonResponse($packages);
    }

    public function show($id) {
        $package = $this->packageModel->findById($id);
        
        if ($package) {
            $this->jsonResponse($package);
        } else {
            $this->jsonResponse(['error' => 'Package not found'], 404);
        }
    }
}

