Email for cooperation: offers@lichi.su

# lichi-keenetic-sdk
**For install:**
```
composer require lichi/keenetic-sdk
```

**Calling the constructor to get started**

```
include "vendor/autoload.php";

use Lichi\Keenetic\ApiProvider;
use GuzzleHttp\Client;
use Lichi\Keenetic\Sdk\LAN\DevicesList;

$client = new Client([
    'base_uri' => "http://192.168.0.1",
    'verify' => false,
    'timeout'  => 30.0,
]);

$apiProvider = new ApiProvider($client, getenv('API_LOGIN'), getenv('API_PASS'));
```

# Get about info
```
$about = new About($apiProvider);
$aboutData = $about->get();
```

# Work with devices
```
$deviceList = new DevicesList($apiProvider);

$active = $deviceList->getActive(true);
$unregistered = $deviceList->getRegistered(false);

$deviceList->registration("Клиент", $unregistered[0]);
$deviceList->unRegistration("38:6b:1c:96:b4:83");

$deviceList->disableInternet("cc:2d:21:6d:d7:99");
$deviceList->enableInternet("cc:2d:21:6d:d7:99");
```

# Get traffic info
```
$traffic = new Traffic($apiProvider);
$trafficData = $traffic->limit(5);
```