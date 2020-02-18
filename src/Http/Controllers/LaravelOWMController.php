<?php

namespace Fourampers\LaravelOWM\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Fourampers\LaravelOWM\LaravelOWM;

class LaravelOWMController extends Controller
{
    /**
     * Ответ с текущей погодой запрошенного города/местоположения
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function currentweather(Request $request)
    {
        $lowm = new LaravelOWM();
        $tz = new \DateTimeZone(config('app.timezone'));

        $city = $request->get('city');
        $coordinates = $request->get('coord');
        $lang = $request->get('lang', 'en');
        $units = $request->get('units', 'metric');

        if ($city === null && $coordinates == null) {
            abort('400','City or coordinates cannot be undefined.');
        }

        $query = ($city) ?: $coordinates;

        try {
            $current_weather = $lowm->getCurrentWeather($query, $lang, $units, true);
        } catch(\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => $e->getCode()]);
        }

        $data = [
            'city' => [
                'id' => $current_weather->city->id,
                'name' => $current_weather->city->name,
                'lat' => $current_weather->city->lat,
                'lon' => $current_weather->city->lon,
                'country' => $current_weather->city->country,
                'population' => $current_weather->city->population
            ],
            'sun' => [
                'rise' => [
                    'date' => $current_weather->sun->rise->setTimezone($tz)->format('Y-m-d H:i:s'),
                    'timestamp' => $current_weather->sun->rise->setTimezone($tz)->getTimestamp()
                ],
                'set' => [
                    'date' => $current_weather->sun->set->setTimezone($tz)->format('Y-m-d H:i:s'),
                    'timestamp' => $current_weather->sun->set->setTimezone($tz)->getTimestamp()
                ]
            ],
            'lastUpdate' => [
                'date' => $current_weather->lastUpdate->setTimezone($tz)->format('Y-m-d H:i:s'),
                'timestamp' => $current_weather->lastUpdate->setTimezone($tz)->getTimestamp()
            ]
        ];

        $data = array_merge($data, $this->parseData($current_weather));

        return response()->json(['status' => 'ok', 'data' => $data]);
    }

    /**
     * Хелпер для парсинга данных
     *
     * @param $obj
     * @return array
     */
    private function parseData($obj)
    {
        $data = [
            'temperature' => [
                'now' => [
                    'value' => $obj->temperature->now->getValue(),
                    'unit' => $obj->temperature->now->getUnit()
                ],
                'min' => [
                    'value' => $obj->temperature->min->getValue(),
                    'unit' => $obj->temperature->min->getUnit()
                ],
                'max' => [
                    'value' => $obj->temperature->max->getValue(),
                    'unit' => $obj->temperature->max->getUnit()
                ]
            ],
            'humidity' => [
                'value' => $obj->humidity->getValue(),
                'unit' => $obj->humidity->getUnit()
            ],
            'pressure' => [
                'value' => $obj->pressure->getValue(),
                'unit' => $obj->pressure->getUnit()
            ],
            'wind' => [
                'speed' => [
                    'value' => $obj->wind->speed->getValue(),
                    'unit' => $obj->wind->speed->getUnit(),
                    'description' => $obj->wind->speed->getDescription(),
                    'description_slug' => Str::slug($obj->wind->speed->getDescription())
                ],
                'direction' => [
                    'value' => $obj->wind->direction->getValue(),
                    'unit' => $obj->wind->direction->getUnit(),
                    'description' => $obj->wind->direction->getDescription(),
                    'description_slug' => Str::slug($obj->wind->direction->getDescription())
                ]
            ],
            'clouds' => [
                'value' => $obj->clouds->getValue(),
                'unit' => $obj->clouds->getUnit(),
                'description' => $obj->clouds->getDescription(),
                'description_slug' => Str::slug($obj->clouds->getDescription())
            ],
            'precipitation' => [
                'value' => $obj->precipitation->getValue(),
                'unit' => $obj->precipitation->getUnit(),
                'description' => $obj->precipitation->getDescription(),
                'description_slug' => Str::slug($obj->precipitation->getDescription())
            ],
            'weather' => [
                'id' => $obj->weather->id,
                'description' => $obj->weather->description,
                'description_slug' => Str::slug($obj->weather->description),
                'icon' => $obj->weather->icon
            ],
        ];

        return $data;
    }
}
