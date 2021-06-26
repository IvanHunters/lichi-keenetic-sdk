<?php

declare(strict_types=1);


namespace Lichi\Keenetic\Sdk\LAN;


use GuzzleHttp\RequestOptions;
use Lichi\Keenetic\ApiProvider;

class DevicesList
{
    /**
     * @var ApiProvider
     */
    private ApiProvider $apiProvider;
    /**
     * @var array|mixed
     */
    private $devices = [];

    public function __construct(ApiProvider $apiProvider) {
        $this->apiProvider = $apiProvider;
        $this->devices = $this->get();
    }

    private function get(): array
    {
        return $this->apiProvider->callMethod(
                "POST",
                "rci/",
                [
                    RequestOptions::BODY => '[{"show":{"ip":{"hotspot":{}}}}]'
                ]
            )[0]['show']['ip']['hotspot']['host'];
    }

    public function getActive(bool $status): array
    {
        $devices = [];
        foreach ($this->devices as $device) {
            if ($device['active'] === $status) {
                $devices[] = $device;
            }
        }
        return $devices;
    }

    public function getDisabled(bool $status): array
    {
        $devices = [];
        foreach ($this->devices as $device) {
            if (($device['access'] === "permit") !== $status) {
                $devices[] = $device;
            }
        }
        return $devices;
    }

    public function getRegistered(bool $status):array
    {
        $devices = [];
        foreach ($this->devices as $device) {
            if ($device['registered'] === $status) {
                $devices[] = $device;
            }
        }
        return $devices;
    }

    public function getAll(): array
    {
        return $this->devices;
    }

    public function registration(string $name, array $userData, ?int $shape = null, bool $pinIp = true)
    {
        $mac = $userData['mac'];
        $interface = $userData['interface']['id'];
        $ip = $userData['ip'];

        $this->apiProvider->callMethod(
            "POST",
            "rci/",
            [
                RequestOptions::BODY => '[{"known":{"host":{"name":"'.$name.'","mac":"'.$mac.'"}}},{"ip":{"hotspot":{"host":[{"mac":"'.$mac.'","permit":true,"policy":{"no":true}}]}}},{"interface":{"'.$interface.'":{"mac":{"access-list":{"address":{"address":"'.$mac.'","no":true}}}}}},{"system":{"configuration":{"save":true}}}]'
            ]
        );
        if ($pinIp) {
            $this->apiProvider->callMethod(
                "POST",
                "/rci/ip/dhcp/host",
                [
                    RequestOptions::JSON => [
                        "mac" =>$mac,
                        "ip" => $ip
                    ]
                ]
            );
        }
        if(!is_null($shape)) {
            $this->apiProvider->callMethod(
                "POST",
                "rci/",
                [
                    RequestOptions::BODY => '{"ip":{"traffic-shape":{"mac":"'.$mac.'","host":{"rate": '.$shape.'}}}}'
                ]
            );
        }
    }

    public function unRegistration(string $mac)
    {
        $this->apiProvider->callMethod(
            "POST",
            "/rci/ip/dhcp/host",
            [
                RequestOptions::JSON => [
                    "mac" =>$mac,
                    "no" => true
                ]
            ]
        );

        $this->apiProvider->callMethod(
            "POST",
            "/rci/known/host",
            [
                RequestOptions::JSON => [
                    "mac" =>$mac,
                    "no" => true
                ]
            ]
        );
    }

    public function disableInternet(string $mac)
    {
        $this->apiProvider->callMethod(
            "POST",
            "/rci/ip/hotspot/host",
            [
                RequestOptions::JSON => [
                    "mac" => $mac,
                    "deny" => true,
                    "policy" => false,
                    "schedule" => false
                ]
            ]
        );
    }

    public function enableInternet($mac){
        $this->apiProvider->callMethod(
            "POST",
            "/rci/ip/hotspot/host",
            [
                RequestOptions::JSON => [
                    "mac" => $mac,
                    "permit" => true,
                    "policy" => false,
                    "schedule" => false
                ]
            ]
        );
    }

}