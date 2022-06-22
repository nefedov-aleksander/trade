serve:
docker-compose up -d

run test:
docker-compose exec php composer test


usage:

use Trade\Api\Client\InfoApiClient;
use Trade\Api\Config\SettingValue;
use Trade\Api\Http\Client\CurlHttpClient;
use Trade\Api\Http\Factory;

$settings = new SettingValue([
    'host' => 'https://payeer.com/api/trade',
    'api-id' => 'bd443f00-092c-4436-92a4-a704ef679e24',
    'secret' => 'api_secret_key'
]);

$http = new CurlHttpClient();

$client = new InfoApiClient(
    new CurlHttpClient(), 
    $settings, 
    new Factory());

$info = $client->getInfo();