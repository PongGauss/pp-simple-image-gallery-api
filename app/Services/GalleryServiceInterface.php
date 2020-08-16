<?php


namespace App\Services;


interface GalleryServiceInterface
{
    public function getUsageConclusionData();
    public function getOverallConclusionData();
    public function getUploadedImages();
}
