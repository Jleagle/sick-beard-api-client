<?php
namespace Jleagle\PHPSickBeard;

class PHPSickBeard
{

    private $url;
    private $apiKey;
    private $arrayBase;

    public function __construct($url, $apiKey, $arrayBase=true)
    {
        $this->url = $url;
        $this->apiKey = $apiKey;
        $this->arrayBase = $arrayBase;
    }

    public function __call($method, $parameters = array())
    {

        $method = str_replace('_', '.', $method);
        $parameters[0]['cmd'] = $method;
        return $this->request($parameters[0]);

    }

    private function request($parameters = array()){

        if (empty($parameters)){
            throw new \Exception("No parameters specified.");
        }

        if (empty($this->url) || empty($this->apiKey)){
            throw new \Exception("No api key or URL set.");
        }

        $url = $this->url.'/api/'.$this->apiKey.'/?';
        $query = http_build_query($parameters);
        $json = file_get_contents($url.$query);
        $array = json_decode($json, $this->arrayBase);

        if (!$array){
            throw new \Exception('Invalid response');
        }

        if ($array['result'] != 'success'){
            if (empty($array['message'])){
                $array['message'] = 'Error';
            }
            throw new \Exception($array['message']);
        }

        return $array['data'];

    }

}
