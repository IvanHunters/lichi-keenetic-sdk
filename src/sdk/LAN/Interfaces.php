<?php

declare(strict_types=1);


namespace Lichi\Keenetic\Sdk\LAN;


use GuzzleHttp\RequestOptions;
use Lichi\Keenetic\ApiProvider;

class Interfaces
{
    /**
     * @var ApiProvider
     */
    private ApiProvider $apiProvider;

    public function __construct(ApiProvider $apiProvider) {
        $this->apiProvider = $apiProvider;
    }

    public function get() {
        return $this->apiProvider->callMethod(
            "GET",
            "rci/interface/",
            []
        );
    }
}