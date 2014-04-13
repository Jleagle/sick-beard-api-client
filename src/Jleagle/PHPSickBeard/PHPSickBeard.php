<?php
namespace Jleagle\PHPSickBeard;

class PHPSickBeard
{

    private $url;
    private $apiKey;

    public function __construct($url, $apiKey)
    {
        $this->url = $url;
        $this->apiKey = $apiKey;
    }

    public function __call($method, $parameters = array())
    {
        $method = str_replace('_', '.', $method);
        $parameters['cmd'] = $method;
        return $this->request($method, $parameters);
    }

    private function request($parameters = array()){

        $url = $this->url.'/api/'.$this->apiKey.'/?';
        $query = http_build_query($parameters);
        $json = file_get_contents($url.$query);
        $json = json_decode($json, true);
        return $json;

    }

}
