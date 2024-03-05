<?php

// config for JsonNaN/LaravelRiotLolApi
return [
    /*
     * API Key for Riot Games, you can get your developer API KEY from https://developer.riotgames.com/
     * You can find the production key in the APPS section by selecting your authorized application
     */
    'api_key' => env('RIOT_LOL_API_KEY'),

    /*
     * API URL for Riot Games, you can find the available URLs in the Riot Games API documentation
     */
    'api_domain' => env('RIOT_LOL_API_DOMAIN', 'api.riotgames.com'),

    /*
     * Region for the Riot Games API, available regions are: americas, asia, europe, sea
     */
    'default_region' => env('RIOT_LOL_REGION', 'americas'),

    /*
     * Platform for the Riot Games API, available platforms are:
     * br1, eun1, euw1, jp1, kr, la1, la2, na1, oc1, tr1, ru, ph2, sg2, th2, tw2, vn2
     */
    'platform' => env('RIOT_LOL_PLATFORM', 'la1'),

    /*
     * Data Dragon version for the current game version, you can find the available versions in the Riot Games API documentation
     */
    'data_dragon_version' => env('RIOT_LOL_DATA_DRAGON_VERSION', '14.3.1'),
];
