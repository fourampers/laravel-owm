<?php 


namespace Fourampers\LaravelOWM;

use Cmfcmf\OpenWeatherMap;
use Http\Factory\Guzzle\RequestFactory;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

class LaravelOWM
{
    /**
     * @var mixed
     */
    protected $config;

    /**
     * @var mixed
     */
    protected $apiKey;

    /**
     * @var RequestFactory
     */
    protected $httpRequestFactory;

    /**
     * @var GuzzleAdapter
     */
    protected $httpClient;

    public function __construct()
    {

        $this->httpRequestFactory = new RequestFactory();

        $this->httpClient = GuzzleAdapter::createWithConfig([]);

        $this->config = config('laravel-owm');

        if ($this->config === null) {
            throw new \Exception('config/laravel-owm.php not found');
        }

        if ($this->config['api_key'] === null) {
            throw new \Exception('laravel-owm.api_key not found');
        }

        $this->apiKey = $this->config['api_key'];
    }

    /**
     * Получить текущую погоду в запрошенном городе/местоположении.
     *
     * Есть два способа указать место для получения информации о погоде:
     *  - Использовать название города: $query должен быть строкой, содержащей название города
     *  - Использовать идентификатор города: $query должен быть целым числом, являющимся идентификатором города
     *
     * @param array|int|string $query
     * @param string $lang
     * @param string $units
     * @param bool $cache
     * @param int $time
     * @return OpenWeatherMap\CurrentWeather
     */
    public function getCurrentWeather($query, $lang = 'en', $units = 'metric', $cache = false, $time = 600)
    {
        $lang = $lang ?: 'en';
        $units = $units ?: 'metric';

        $owm = new OpenWeatherMap($this->apiKey, $this->httpClient, $this->httpRequestFactory);
        return $owm->getWeather($query, $units, $lang);
    }
}