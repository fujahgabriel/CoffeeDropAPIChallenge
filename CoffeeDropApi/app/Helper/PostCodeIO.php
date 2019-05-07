<?php
namespace App\Helper;

class PostCodeIO
{
    private static $ENDPOINT = 'https://api.postcodes.io/postcodes';
    //call to postcode validation 
    public static function validate($code) : bool {
        $url = static::$ENDPOINT.'/'.str_replace(" ","", $code).'/validate';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        //get validation data
        $content = json_decode($response->getBody(), true);
        if(isset($content["result"])){
            return $content["result"];
        }
        return false;
    }
    //call to retrieve postcode data 
    public static function get($code) : array {
        $url = static::$ENDPOINT.'/'.str_replace(" ","", $code);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);

        //get postcode data
        $content = json_decode($response->getBody(), true);
        if(isset($content["result"])){
            return $content["result"];
        }
        return [];
    }

}