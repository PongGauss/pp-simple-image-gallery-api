<?php


namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client as OClient;

use App\Image;

class GalleryService implements GalleryServiceInterface
{
    private $imageModel;

    public function __construct(Image $imageModel)
    {
        $this->imageModel = $imageModel;
    }

    public function getUsageConclusionData() {
        return $this->imageModel->queryImageUsageConclusionData();
    }

    public function getOverallConclusionData() {
        return $this->imageModel->queryImageOverallConclusionData();
    }
}
