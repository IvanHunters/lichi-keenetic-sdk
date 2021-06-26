<?php


namespace Lichi\Keenetic;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use RuntimeException;

class ApiProvider
{
    private Client $client;
    protected string $apiLogin;
    private string $apiPassword;
    private $cookie = "";

    /**
     * ApiProvider constructor.
     * @param Client $client
     * @param string $apiLogin
     * @param string $apiPassword
     */
    public function __construct(Client $client, string $apiLogin, string $apiPassword)
    {
        $this->client = $client;
        $this->apiLogin = $apiLogin;
        $this->apiPassword = $apiPassword;
        $this->Authorize();
    }

    /**
     * @param string $typeRequest
     * @param string $method
     * @param array $params
     * @param bool $useCookie
     * @return mixed
     */
    public function callMethod(string $typeRequest, string $method, array $params = [], bool $useCookie = true)
    {
        usleep(380000);

        if($useCookie){
            $params['headers'] = [
                'Cookie' => [" _authorized=admin; sysmode=router; _authorized=admin; " .$this->cookie[0]]
            ];
        }
        try {
            $response = $this->client->request($typeRequest, $method, $params);
        } catch (GuzzleException $exception){
            if ($exception->getCode() !== 401) {
                $response = empty($exception->getResponse()->getBody(true))? $exception->getMessage(): $exception->getResponse()->getBody(true);
                throw new RuntimeException(sprintf(
                    "API ERROR, message: %s",
                    $response,
                ));
            } else {
                $response = $exception->getResponse();
            }
        }

        /** @var string $content */
        $content = $response->getBody()->getContents();

        /** @var array $response */
        $responseData = json_decode($content, true);
        if(is_null($responseData)) {
            return $response;
        }

        return $responseData;
    }

    private function Authorize()
    {
        $unauthorizedHeaders = $this->callMethod(
            "GET",
            "/auth",
            [],
            false
        )->getHeaders();

        $this->cookie = $unauthorizedHeaders['Set-Cookie'];

        $responseAuth = $this->callMethod(
            "POST",
            "/auth" ,
            [
                RequestOptions::JSON => [
                    'login' => $this->apiLogin,
                    'password' => $this->hashingPassword($unauthorizedHeaders)
                ],
            ]
        );
        $response = $this->callMethod(
            "GET",
            "/auth",
            []
        );
        if ($response->getStatusCode() === 401) {
            throw new RuntimeException("Bad login or password!");
        }
    }

    private function hashingPassword(array $headers) {
        $md5 = md5($this->apiLogin.':'.$headers['X-NDM-Realm'][0].':'.$this->apiPassword);
        return hash('sha256', $headers['X-NDM-Challenge'][0].$md5);
    }
}