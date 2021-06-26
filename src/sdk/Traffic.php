<?php

declare(strict_types=1);


namespace Lichi\Keenetic\Sdk;


use GuzzleHttp\RequestOptions;
use Lichi\Keenetic\ApiProvider;

class Traffic
{
    /**
     * @var ApiProvider
     */
    private ApiProvider $apiProvider;

    public function __construct(ApiProvider $apiProvider) {
        $this->apiProvider = $apiProvider;
    }

    public function limit(int $limitUsers = 5){
        return $this->apiProvider->callMethod(
            "POST",
            "rci/",
            [
                RequestOptions::JSON => [
                    [
                        "show" => [
                            "ip" => [
                                "hotspot" => [
                                    "summary" => [
                                        "attribute" => "sumbytes",
                                        "count" => $limitUsers,
                                        "detail" => 0
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        )[0]['show']['ip']['hotspot']['summary']['host'];
    }

}