<?php
namespace Jleagle\PHPSickBeard;

use \GuzzleHttp\Client;

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

    private function request($parameters = array())
    {

        if (empty($parameters))
        {
            throw new \Exception("No parameters specified.");
        }

        if (empty($this->url) || empty($this->apiKey))
        {
            throw new \Exception("No api key or URL set.");
        }

        // Build URL
        $url = $this->url.'/api/'.$this->apiKey.'/?';
        $query = http_build_query($parameters);

        // Guzzle
        $client = new Client();
        $response = $client->get($url.$query);

        if ($response->getStatusCode() != 200)
        {
            throw new \Exception('Invalid response');
        }

        $body = $response->getBody();

        if (strpos($response->getHeader('content-type'), 'json') !== false)
        {
            $array = json_decode($body, $this->arrayBase);

            if (!$array)
            {
                throw new \Exception('Invalid response');
            }

            if (isset($array['result']) && $array['result'] != 'success')
            {
                if (empty($array['message'])){
                    $array['message'] = 'Error';
                }
                throw new \Exception($array['message']);
            }

            return $array['data'];
        }
        else
        {
            return $body;
        }

    }

}
