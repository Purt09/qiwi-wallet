<?php
declare(strict_types=1);

namespace Purt09\QiwiWallet\Traits;

use GuzzleHttp\Client;

trait Api
{
    static $API_URL = "https://edge.qiwi.com";

    private $token = '';

    private $phone = '';

    public function __construct(string $token, string $phone)
    {
        $this->token = $token;
        $this->phone = $phone;
    }


    private function sendRequest($method, array $content = [], string $proxy = '', bool $isPost = true) {
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, self::$API_URL . $method);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Accept: application/json',
        //     'Content-Type: application/json',
        //     'Authorization: Bearer ' . $this->token
        // ]);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $result = curl_exec($ch);
        // curl_close($ch);
        // return json_decode($result, true);
        $uri = self::$API_URL . $method;
        $client = new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
        if($isPost) {
            $params = [
                'form_params' => $content,
                'allow_redirects' => [
                    'strict' => true
                ],
            ];
        } else {
            $params = [
                'query' => $content,
            ];

        }
        if (!empty($proxy))
            $params['proxy'] = $proxy;
        if($isPost) {
            $result = $client->post($uri, $params);
        } else {
            $result = $client->get($uri, $params);
        }
        return json_decode($result->getBody()->getContents(), true);
    }
}