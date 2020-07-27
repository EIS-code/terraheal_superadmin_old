<?php

namespace App\Repositories\Massage;

use App\Repositories\BaseRepository;
use App\Massage;
use App\Shop;
use App\City;
use App\SessionType;
use DB;
use GuzzleHttp\Client;

class MassageRepository extends BaseRepository
{
    protected $massage;

    public function __construct()
    {
        parent::__construct();
        $this->massage = new Massage();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->massage->all();
    }

    public function getWhere($column, $value)
    {
        return $this->massage->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->massage->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Massage found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {}

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(array $data, int $limit = 10)
    {
        $query  = (!empty($data['q'])) ? $data['q'] : NULL;
        $limit  = (!is_numeric($limit)) ? 10 : $limit;
        $shopId = (!empty($data['shop_id'])) ? (int)$data['shop_id'] : NULL;

        $getMassages = $this->massage->where("name", "LIKE", "%{$query}%")->with(['timing' => function($qry) {
            $qry->with('pricing');
        }])->limit($limit)->get();

        // Get shop details.
        $massageCenters = [];
        if (!empty($shopId)) {
            $shops = Shop::where('id', $shopId)->first();
            if (!empty($shops)) {
                $data['latitude']  = $shops->latitude;
                $data['longitude'] = $shops->longitude;

                $massageCenters = $this->getMassageCenters($data, false);
            }
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'Massage found successfully !',
            'data' => $getMassages,
            'massage_centers' => $massageCenters
        ]);
    }

    public function getMassageCenters(array $data, $isApi = true, int $limit = 10)
    {
        $latitude  = (!empty($data['latitude'])) ? $data['latitude'] : NULL;
        $longitude = (!empty($data['longitude'])) ? $data['longitude'] : NULL;
        $limit     = (!is_numeric($limit)) ? 10 : $limit;
        $distance  = 45;

        $shopTableName = Shop::getTableName();
        $cityTableName = City::getTableName();
        $query         = Shop::query();

        $selectStatements = "{$shopTableName}.*, latitude, longitude, (6371 * 2 * ASIN(SQRT(POWER(SIN((37.38714000 - latitude)* PI() / 180 / 2), 2) + COS(37.38714000* PI() / 180) * COS(latitude* PI() / 180) * POWER(SIN((-122.08323500 - longitude) * PI() / 180 / 2) ,2)))) AS distance";
        $whereStatements  = "longitude BETWEEN ({$longitude} - {$distance} / COS(RADIANS({$latitude})) * 69) AND ({$longitude} + {$distance} / COS(RADIANS({$latitude})) *69) AND latitude BETWEEN ({$latitude} - ({$distance} / 69)) AND ({$latitude} + ({$distance} / 69))";

        $query = $query->select(DB::raw($selectStatements))
                       ->leftJoin($cityTableName, $shopTableName . '.city_id', '=', $cityTableName . '.id')
                       ->whereRaw($whereStatements)
                       ->get();

        // TODO : Check current city and show that city massages.
        /* $client   = new Client();
        $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json', ['query' => [
            'latlng' => "{$latitude} , {$longitude}", 
            'key'    => env('GOOGLE_API_KEY'),
        ]]); */

        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'dev') {
            $latLong[] = [
                'latitude'  => 37.38633900,
                'longitude' => -122.08582300
            ];
            $latLong[] = [
                'latitude'  => 37.38714000,
                'longitude' => -122.08323500
            ];
            $latLong[] = [
                'latitude'  => 37.39388500,
                'longitude' => -122.07891600
            ];
            $latLong[] = [
                'latitude'  => 37.40265300,
                'longitude' => -122.07935400
            ];
            $latLong[] = [
                'latitude'  => 37.39401100,
                'longitude' => -122.09552800
            ];
            $latLong[] = [
                'latitude'  => 37.40172400,
                'longitude' => -122.11464600
            ];
            for ($i = 0; $i < 5; $i++) {
                $returnData[] = [
                    'id' => 1,
                    'name' => 'Shop 0' . $i,
                    'address' => 'Address 0' . $i,
                    'latitude' => $latLong[$i]['latitude'],
                    'longitude' => $latLong[$i]['longitude'],
                    'total_services' =>  25,
                    'center_hours' => [
                        'mon' => '09AM to 05PM',
                        'tue' => '09AM to 05PM',
                        'wed' => '09AM to 05PM',
                        'thu' => '09AM to 05PM',
                        'fri' => '09AM to 05PM',
                        'sat' => '09AM to 05PM',
                        'sun' => NULL
                    ]
                ];
            }

            if (!$isApi) {
                return $returnData;
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'Massage center found successfully !',
                'data' => $returnData
            ]);
        } else {

            if (!$isApi) {
                return $returnData;
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'Massage center found successfully !',
                'data' => $query
            ]);
        }
    }
 
    public function getMassageSessions()
    {
        $getSessionTypes = SessionType::where('is_removed', SessionType::$notRemoved)->get();

        return response()->json([
            'code' => 200,
            'msg'  => 'Massage sessions found successfully !',
            'data' => $getSessionTypes
        ]);
    }

    public function errors()
    {}
}
