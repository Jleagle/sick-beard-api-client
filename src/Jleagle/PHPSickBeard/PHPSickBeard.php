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
        $parameters = $parameters[0];
        $method = str_replace('_', '.', $method);
        $parameters['cmd'] = $method;
        return $this->request($parameters);
    }

    private function request($parameters = array()){

        if (empty($parameters)){
            throw new \Exception("No parameters specified.");
        }

        $url = $this->url.'/api/'.$this->apiKey.'/?';
        $query = http_build_query($parameters);
        $json = file_get_contents($url.$query);
        $array = json_decode($json, true);

        if (!$array){
            throw new \Exception('Invalid response');
        }

        if ($array['result'] != 'success'){
            throw new \Exception($array['message']);
        }

        return $array;

    }

}
