<?php


namespace Tnaffh\Routee;


use GuzzleHttp\Client as Guzzle;

class Routee
{
    protected $api;
    protected $authendpoint = 'https://auth.routee.net/oauth/token';
    protected $apiendpoint = 'https://connect.routee.net/sms';
    protected $accessToken;

    public function __construct()
    {
        $this->accessToken = $this->authenticate()->getBody()->getContents()['access_token'];
        $this->api = new Guzzle($this->getHeaders());
    }

    public function sendMessage(String $message,String $to,String $from){
        try{
            return $this->api->post($this->apiendpoint,[
                'json'=>[
                    'body' => $message,
                    'to'=> $to,
                    'from'=>$from
                ]
            ]);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage(), 422);
        }
    }

    protected function getHeaders()
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer '.$this->accessToken,
                'Content-Type' => 'application/json'
            ]
        ];
    }
    protected function getAuthHeaders()
    {
        return [
            'headers' => [
                'Authorization' => 'Basic '.$this->getBase64String(),
                'content-type' => 'application/json'
            ]
        ];
    }

    /**
     * Encode the resulting string using Base64
     * -> applicationid:applicationsecret
     * @return string
     */
    protected function getBase64String(){
        return base64_encode(config('routee.app_id').':'.config('routee.app_secret'));
    }

    protected function authenticate(){
        $client = new Guzzle($this->getAuthHeaders());
        try{
            return $client->post($this->authendpoint,[
                'json' => [
                    'grant_type'=>'client_credentials',
                ]
            ]);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage(), 422);
        }
    }
}