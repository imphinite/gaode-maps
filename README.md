## Collection of Gaode Web Services API for Laravel 5 
**DEVELOPMENT IN PROGRESS**

Provides convenient way of setting up and making requests to Web Services API from [Laravel](http://laravel.com/) application. 

For services documentation, API key and Usage Limits visit [Gaode Web Services API](https://lbs.amap.com/api/webservice/summary/) and [Web Services API Usage Limits And Restrictions](https://lbs.amap.com/api/webservice/guide/tools/flowlevel/).

**Note that this package is under development. Most Features are not implemented yet. Feel free to collaborate on this project!

**SPECIAL THANKS TO [Alexpechkarev](https://github.com/alexpechkarev/). Web Services Engine is borrowed from [Alexpechkarev/google-maps](https://github.com/alexpechkarev/google-maps/).


Features
------------
* [Place Search API](https://lbs.amap.com/api/webservice/guide/api/search/)
* [Batch Request API](https://lbs.amap.com/api/webservice/guide/api/batchrequest/)

Features TO-DO List
------------
* [Geocoding/Reverse Geocoding API](https://lbs.amap.com/api/webservice/guide/api/georegeo/)
* [Directions API](https://lbs.amap.com/api/webservice/guide/api/direction/)
* [District Query API](https://lbs.amap.com/api/webservice/guide/api/district/)
* [Geolocation API](https://lbs.amap.com/api/webservice/guide/api/ipconfig/)
* [Roads API](https://lbs.amap.com/api/webservice/guide/api/autograsp/)
* [Static Maps API](https://lbs.amap.com/api/webservice/guide/api/staticmaps/)
* [Coordinate Convert API](https://lbs.amap.com/api/webservice/guide/api/convert/)
* [Weather API](https://lbs.amap.com/api/webservice/guide/api/weatherinfo/)
* [Auto Complete API](https://lbs.amap.com/api/webservice/guide/api/inputtips/)
* [Traffic API](https://lbs.amap.com/api/webservice/guide/api/trafficstatus/)
* [Geofence API](https://lbs.amap.com/api/webservice/guide/api/geofence_service/)


Dependency
------------
* [PHP cURL](http://php.net/manual/en/curl.installation.php)
* [PHP 5](http://php.net/)


Installation
------------

Issue following command in console:

```php
composer require imphinite/gaode-maps
```

Alternatively  edit composer.json by adding following line and run **`composer update`**
```php
"require": { 
		....,
		"imphinite/gaode-maps",
	
	},
```

Configuration
------------

Register package service provider and facade in 'config/app.php'

```php
'providers' => [
    ...
    'GaodeMaps\ServiceProvider\GaodeMapsServiceProvider',
]

'aliases' => [
    ...
    'GaodeMaps' => 'GaodeMaps\Facade\GaodeMapsFacade',
]
```


Publish configuration file using **`php artisan vendor:publish --tag=gaodemaps --force`** or simply copy package configuration file and paste into **`config/gaodemaps.php`**

Open configuration file **`config/gaodemaps.php`** and add your service key
```php
    /*
    |----------------------------------
    | Service Keys
    |------------------------------------
    */
    
    'key'       => 'YOUR GAODE API KEY HERE',
```

If you like to use different keys for any of the services, you can overwrite master API Key by specifying it in the `service` array for selected web service. 


Usage
------------

Here is an example of making request to Places Search API:
```php
$service = GaodeMaps::load('nearbysearch')
        ->setParam([
            'location'          => '120.392164,36.056936',  // Longitude first in Chinese convension 
            'keywords'          => '餐厅',
            'radius'            => 5000,
            'page'              => 1,
            'extensions'        => 'all',
            'output'            => 'json'
        ]);
$response = $service->get();
...
```

Alternatively parameters can be set using `setParamByKey()` method. For deeply nested array use "dot" notation as per example below.

```php
$endpoint = GaodeMaps::load('nearbysearch')
        ->setParamByKey('location', '120.392164,36.056936')
        ->setParamByKey('keywords', '餐厅') //return $this
...
```

Another example showing request to Batch Request service when requesting multiple places' details:

```php
$batch_urls = array();
array_push($batch_urls, (object) array(
    'url' => GaodeMaps::load('placedetails')
        ->setParam(['id' => $place->id)
        ->getBatchUrl()
    )
);

$service = GaodeMaps::load('batchrequest')
        ->setParam([
            'ops'               => $batch_urls
        ]);
$response = $batch_service->get();
...
```

Available methods
------------

* [`load( $serviceName )`](#load)
* [`setParamByKey( $key, $value )`](#setParamByKey)
* [`setParam( $parameters )`](#setParam)
* [`getBatchUrl()`](#getBatchUrl)
* [`get()`](#get)

---

<a name="load"></a>
**`load( $serviceName )`** - load web service by name 

Accepts string as parameter, web service name as specified in configuration file.  
Returns reference to it's self.

```php
GaodeMaps::load('nearbysearch') 
... 

```

---

<a name="setParamByKey"></a>
**`setParamByKey( $key, $value )`** - set request parameter using key:value pair

Accepts two parameters:
* `key` - body parameter name
* `value` - body parameter value 

Deeply nested array can use 'dot' notation to assign value.  
Returns reference to it's self.

```php
$service = GaodeMaps::load('nearbysearch')
        ->setParamByKey('location', '120.392164,36.056936')
        ->setParamByKey('keywords', '餐厅') //return $this
...
```

---

<a name="setParam"></a>
**`setParam( $parameters )`** - set all request parameters at once

Accepts array of parameters  
Returns reference to it's self.

```php
$service = GaodeMaps::load('nearbysearch')
        ->setParam([
            'location'          => '120.392164,36.056936',  // Longitude first in Chinese convension 
            'keywords'          => '餐厅',
            'radius'            => 5000,
            'page'              => 1,
            'extensions'        => 'all',
            'output'            => 'json'
        ]); // return $this
...
```

---

<a name="getBatchUrl"></a>
**`getBatchUrl()`** - generate a Url of this service for Batch Request web service

Returns Batch Request url of this service.

```php
$url = GaodeMaps::load('nearbysearch')
    ->setParam([
        'location'          => '120.392164,36.056936',  // Longitude first in Chinese convension 
        'keywords'          => '餐厅',
        'radius'            => 5000,
        'page'              => 1,
        'extensions'        => 'all',
        'output'            => 'json'
    ])->getBatchUrl();
...
```

---

<a name="get"></a>
* **`get()`** - perform web service request (irrespectively to request type POST or GET )

Returns web service response in the format specified by **`setEndpoint()`** method, if omitted defaulted to `JSON`. 
Use `json_decode()` to convert JSON string into PHP variable. See [Processing Response](https://developers.gaode.com/maps/documentation/webservices/#Parsing) for more details on parsing returning output.

```php
$response = GaodeMaps::load('nearbysearch')
        ->setParam([
            'location'          => '120.392164,36.056936',  // Longitude first in Chinese convension 
            'keywords'          => '餐厅',
            'radius'            => 5000,
            'page'              => 1,
            'extensions'        => 'all',
            'output'            => 'json'
        ])->get();

var_dump( json_decode( $response ) );  // output 
...
```

/*
{
    "status": "1",
    "count": "274",
    "info": "OK",
    "infocode": "10000",
    "suggestion": {
        "keywords": [],
        "cities": []
    },
    "pois": [
        "0": {
            "id": "B0FFFF4RX1",
            "tag": "牛道红花牛三品,菌类拼盘,新快猪上五花,牛舌厚切,石锅拌饭,红花三拼,红花牛芝士盖饭,烤蘑菇,红花牛肉,猪雪花肩胛肉,牛舌薄切,生拌牛肉,迷你现压朝鲜冷面,炒乌冬面,牛肩胛肉,酱香牛腿芯,海鲜饼,牛肋脊,泡菜饼,烤牛肉,牛仔骨,海鲜乌冬面,红花牛特色三样,烤五花肉,极品一口牛排",
            "name": "新快牛道红花牛馆(百丽广场店)",
            "type": "餐饮服务;中餐厅;特色/地方风味餐厅",
            ...
*/

---

License
-------

Collection of Gaode Web Services API for Laravel 5 is released under the MIT License. 

---

MIT License

Copyright (c) 2017 Yan Lin Wang

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
