<?php


namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Image extends Model
{
    public function queryImageUsageConclusionData() {
        $response = DB::table('images')
            ->select('image_ext', DB::raw('count(1) as count_ext'), DB::raw('sum(image_size) as sum_size_ext'))
            ->groupBy('image_ext')
            ->get();

        return $response;
    }

    public function queryImageOverallConclusionData() {
        $response = DB::table('images')
            ->select(DB::raw('count(1) as count_all'), DB::raw('sum(image_size) as sum_size_all'))
            ->get();

        return $response;
    }
}
