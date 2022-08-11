<?php
declare(strict_types=1);

namespace Purt09\QiwiWallet\Traits;

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


    private function sendRequest($method, array $content = [], $post = false) {
        $ch = curl_init();
        if ($post) {
            curl_setopt($ch, CURLOPT_URL, self::$API_URL . $method);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
        } else {
            curl_setopt($ch, CURLOPT_URL, self::$API_URL . $method . '?' . http_build_query($content));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
}