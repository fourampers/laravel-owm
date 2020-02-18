# Laravel-OWM (Open Weather Map)
## Адаптер для компонента [OpenWeatherMap-PHP-Api](https://github.com/cmfcmf/OpenWeatherMap-PHP-Api) [(@cmfcmf)](https://github.com/cmfcmf)
### Адаптер позволяет реализовать OpenWeatherMap-PHP-Api в Laravel-проекте
##### 1. Требования
Для работы потребуется Laravel версии не ниже 6
##### 2. Установка
Устаналиваем пакет из composer
`composer require fourampers/laravel-owm`
Публикуем конфигурационный файл (config/laravel-owm.php)
`php artisan vendor:publish --provider="Fourampers\LaravelOWM\LaravelOWMServiceProvider"`
#### 3. Использование
По умолчанию включен только один GET маршрут, по которому предоставляются данные о погоде в формате JSON.
В качестве параметра можно задавать как имя города
`/api/weather?city=krasnodar`
так и идентификатор города
`/api/weather?city=542415`