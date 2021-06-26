<?php

declare(strict_types=1);


namespace Lichi\Keenetic\Sdk;


use GuzzleHttp\RequestOptions;
use Lichi\Keenetic\ApiProvider;

class About
{
    /**
     * @var ApiProvider
     */
    private ApiProvider $apiProvider;

    public function __construct(ApiProvider $apiProvider) {
        $this->apiProvider = $apiProvider;
    }

    public function get(): array
    {
        return $this->apiProvider->callMethod(
            "POST",
            "/rci/components/list",
            [
                RequestOptions::BODY => '{}'
            ]
        );
    }
}