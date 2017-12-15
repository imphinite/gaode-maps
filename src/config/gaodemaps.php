<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Will be used for all web services, 
    | unless overwritten bellow using 'key' parameter
    |
    |
    */
    
    'key'       => 'YOUR GAODE API KEY HERE',
        
    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    |
    | Will be used for all web services to verify 
    | SSL peer (SSL certificate validation)
    |
     */

    'ssl_verify_peer' => FALSE,    

    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    | url - web service URL
    | type - request type POST or GET
    | key - API key, if different to API key above
    | responseDefaultKey - specify default field value to be retruned when calling getByKey()
    | param - accepted request parameters
    |
    */

    'service' => [
        
        'textsearch' => [
            'url'                   => 'http://restapi.amap.com/v3/place/text',
            'type'                  => 'GET',
            'key'                   =>  null,
            'responseDefaultKey'    => 'pois',
            'param'                 => [
                                        'keywords'          => null,
                                        'types'             => null,
                                        'city'              => null,
                                        'citylimit'         => null,
                                        'children'          => null,
                                        'offset'            => 20,
                                        'page'              => null,
                                        'building'          => null,
                                        'floor'             => null,
                                        'extensions'        => null,
                                        'sig'               => null,
                                        'output'            => null,
                                        'callback'          => null,
                                        ]
        ],


        'nearbysearch' => [
            'url'                   => 'http://restapi.amap.com/v3/place/around',
            'type'                  => 'GET',
            'key'                   =>  null,
            'responseDefaultKey'    => 'pois',
            'param'                 => [
                                        'location'          => null,
                                        'keywords'          => null,
                                        'types'             => null,
                                        'city'              => null,
                                        'radius'            => null,
                                        'sortrule'          => 'distance',
                                        'offset'            => 20,
                                        'page'              => null,
                                        'extensions'        => null,
                                        'sig'               => null,
                                        'output'            => null,
                                        'callback'          => null,
                                        ]
        ],

        
        'polygonsearch' => [
            'url'                   => 'http://restapi.amap.com/v3/place/polygon',
            'type'                  => 'GET',
            'key'                   =>  null,                          
            'responseDefaultKey'    => 'pois',
            'param'                 => [
                                        'polygon'           => null,
                                        'keywords'          => null,
                                        'types'             => null,
                                        'offset'            => 20,
                                        'page'              => null,
                                        'extensions'        => null,
                                        'sig'               => null,
                                        'output'            => null,
                                        'callback'          => null,
                                        ]
        ],


        'placedetails' => [
            'url'                   => 'http://restapi.amap.com/v3/place/detail',
            'type'                  => 'GET',
            'key'                   =>  null,                      
            'responseDefaultKey'    => 'pois',
            'param'                 => [
                                        'id'                => null,
                                        'sig'               => null,
                                        'output'            => null,
                                        'callback'          => null,
                                        ]
        ],

        
        'batchrequest' => [
            'url'                   => 'http://restapi.amap.com/v3/batch',
            'type'                  => 'POST',
            'key'                   =>  null,
            'param'                 => [
                                        'ops'               => [],
                                        ]
        ],

    ],

];
