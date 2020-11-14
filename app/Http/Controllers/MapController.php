<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function distance($postcode)
    {
        $latlan = self::getcoords($postcode);
        $url = "https://api.distancematrix.ai/maps/api/distancematrix/json?&key=7U0LZhREnyqHOdFcwtAzhY02z4Fsr&origins=51.729157,0.478027&destinations=";
        $url = $url.$latlan;
        $data   = @file_get_contents($url);
        $result = json_decode($data, true);
        return $result;
    }

    public function getcoords($postcode){
        $url = "http://api.postcodes.io/postcodes/".$postcode;
        $data   = @file_get_contents($url);
        $data = json_decode($data, true);
        $result = $data['result'];
        return $result['latitude'].','.$result['longitude'];
    }
}
