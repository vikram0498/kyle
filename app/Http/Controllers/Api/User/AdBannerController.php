<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\AdvertiseBanner;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdBannerController extends Controller
{
    public function getBanner($page)
    {
        $currentDate = now()->toDateString();
        $currentTime = now()->toTimeString();

        /*$banner = AdvertiseBanner::where('page_type', $page)
            ->where('status', 1) 
            ->whereDate('end_date', '>=', $currentDate)
            ->where(function ($query) use ($currentTime) {
                $query->whereNull('start_time')
                    ->orWhere('start_time', '<=', $currentTime);
            })
            ->where(function ($query) use ($currentTime) {
                $query->whereNull('end_time')
                    ->orWhere('end_time', '>=', $currentTime);
            })
            ->first();
        */
        
        $currentDateTime = Carbon::now();
        $banner = AdvertiseBanner::where('page_type', $page)
        ->where('status', 1)
        ->where(function ($query) use ($currentDateTime) {
            $query->whereNull('end_date')
                  ->orWhereRaw("CONCAT(end_date, ' ', COALESCE(end_time, '23:59:59')) >= ?", [$currentDateTime]);
        })
        ->where(function ($query) use ($currentDateTime) {
            $query->whereNull('start_date')
                  ->orWhereRaw("CONCAT(start_date, ' ', COALESCE(start_time, '00:00:00')) <= ?", [$currentDateTime]);
        })
        ->first();

        if ($banner)
        {        
            $data = [
                'advertiser_name' => $banner->advertiser_name ?? '',
                'ad_name' => $banner->ad_name ?? '',
                'target_url' => $banner->target_url ?? '',
                'page_type' => $banner->page_type ?? '',
                'start_date' => $banner->start_date ? $banner->start_date->format('d-m-Y') : null,
                'end_date' => $banner->end_date ? $banner->end_date->format('d-m-Y') : null,
                'start_time' => $banner->start_time ? $banner->start_time->format('H:i:s') : null,
                'end_time' => $banner->end_time ? $banner->end_time->format('H:i:s') : null,
                'created_at' => $banner->created_at ? $banner->created_at->format('d-m-Y') : null,
                'image' => $banner->adBannerImage ? $banner->image_url : asset('images/default-img.jpg'),
                'is_expired' => false
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ],200);
        }

        return response()->json([
            'success' => true,
            'data' => ['is_expired' => true]
        ],200);
    }
}


