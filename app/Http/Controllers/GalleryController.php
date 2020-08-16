<?php

namespace App\Http\Controllers;
use App\Services\GalleryServiceInterface;

use Illuminate\Http\Request;

class GalleryController extends BaseApiController
{
    private $galleryService;

    public function __construct(GalleryServiceInterface $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    public function galleryUsageConclusion() {
        $resp = $this->galleryService->getUsageConclusionData();
        return $this->response($resp, 200);
    }

    public function galleryOverallConclusion() {
        $resp = $this->galleryService->getOverallConclusionData();
        return $this->response($resp, 200);
    }
}
