<?php

namespace Sakurairo\Http;

use GuzzleHttp\Client;

class Http
{
    public $client;
    public $httpResponse;
    public $response;

    public function __construct($client)
    {
        if ($client) {
            $this->client = $client;
        } else {
            $this->client = new Client([
                'verify' => true,
                'timeout' => 10,
                'http_errors' => false,
                'allow_redirects' => [
                    'protocols' => ['https'],
                ],
            ]);
        }
    }

    public function __call($name, $arguments)
    {
        $this->response = call_user_func_array([$this->httpResponse, $name], $arguments);
        return $this;
    }

    private function strFilter(mixed $str)
    {
        $regex = '/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\/|\;|\'|\`|\-|\=|\\\|\|/';
        if (is_string($str)) {
            return preg_replace($regex, '', $str);
        } else {
            return $str;
        }
    }

    public function toJson(bool $options = false)
    {
        $json = json_decode($this->response, true);
        $responseArray = [];
        foreach ($json as $key => $value) {
            $_key = $this->strFilter($key);
            $_value = $this->strFilter($value);
            $responseArray[$_key] = $_value;
        }
        return $options ? $responseArray : (object)$responseArray;
    }

    public function toArray()
    {
        return $this->toJson(true);
    }

    public function toString()
    {
        return $this->strFilter((string)$this->response);
    }

    public function post(string $url, array $params = [], string $contentType = 'form_params')
    {
        $response = $this->client->request('POST', $url, [$contentType => $params]);
        $this->httpResponse = $response;
        return $this;
    }

    public function get(string $url, array $params = [])
    {
        $response = $this->client->request('GET', $url, ['query' => $params]);
        $this->httpResponse = $response;
        return $this;
    }
}
