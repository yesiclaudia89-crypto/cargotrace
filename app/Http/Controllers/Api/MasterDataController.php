<?php

namespace App\Http\Controllers\Api;

use App\Models\Carrier;
use App\Models\Location;
use Illuminate\Http\JsonResponse;

class MasterDataController extends BaseApiController
{
    /**
     * Get list of active carriers (Slide 13).
     */
    public function carriers(): JsonResponse
    {
        $carriers = Carrier::where('is_active', true)->get();

        return $this->sendResponse($carriers, 'Daftar maskapai pelayaran kargo (carriers).');
    }

    /**
     * Get list of ports and logistical locations with GPS coordinates (Slide 13).
     */
    public function locations(): JsonResponse
    {
        $locations = Location::all();

        return $this->sendResponse($locations, 'Daftar pelabuhan & lokasi hub logistik.');
    }
}
