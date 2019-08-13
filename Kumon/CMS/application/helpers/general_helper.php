<?php

function getLocationByLatLng($lat, $lng){

    $country = "";
    $state = "";
    $city = "";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false&language=en');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = json_decode(curl_exec($ch), true);

    foreach($output["results"][0]["address_components"] as $addressComponet) {

        if(in_array('locality', $addressComponet["types"])) {
            $city = $addressComponet["long_name"];
        }

        if(in_array('administrative_area_level_1', $addressComponet["types"])) {
            $state = $addressComponet["long_name"];
        }

        if(in_array('country', $addressComponet["types"])) {
            $country = $addressComponet["long_name"];
        }

    }

    $data = array();
    $data["country"] = $country;
    $data["state"] = $state;
    $data["city"] = $city;

    return $data;

}

function getOS() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
        '/windows nt 10/i'      =>  'Windows',
        '/windows nt 6.3/i'     =>  'Windows',
        '/windows nt 6.2/i'     =>  'Windows',
        '/windows nt 6.1/i'     =>  'Windows',
        '/windows nt 6.0/i'     =>  'Windows',
        '/windows nt 5.2/i'     =>  'Windows',
        '/windows nt 5.1/i'     =>  'Windows',
        '/windows xp/i'         =>  'Windows',
        '/windows nt 5.0/i'     =>  "Windows",
        '/windows me/i'         =>  'Windows',
        '/win98/i'              =>  'Windows',
        '/win95/i'              =>  'Windows',
        '/win16/i'              =>  'Windows',
        '/macintosh|mac os x/i' =>  'MacOS',
        '/mac_powerpc/i'        =>  'MacOS',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iOS',
        '/ipod/i'               =>  'iOS',
        '/ipad/i'               =>  'iOS',
        '/android/i'            =>  'Android'
    );

    foreach ($os_array as $regex => $value) {

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }

    return $os_platform;

}