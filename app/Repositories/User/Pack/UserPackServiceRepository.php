<?php

namespace App\Repositories\User\Pack;

use App\Repositories\BaseRepository;
use App\UserPackMassage;
use DB;

class UserPackServiceRepository extends BaseRepository
{
    protected $userPackMassage;

    public function __construct()
    {
        parent::__construct();
        $this->userPackMassage = new UserPackMassage();
    }

    public function create(array $data)
    {}

    public function all()
    {
        return $this->userPackService->all();
    }

    public function getWhere($column, $value, $isApi = false)
    {}

    public function getPackServices(array $data)
    {
        $userPackId = (!empty($data['user_pack_id'])) ? (int)$data['user_pack_id'] : false;

        if (!empty($userPackId)) {
            $return      = [];
            $getMassages = $this->userPackMassage->where('user_pack_id', $userPackId)->get();

            if (!empty($getMassages) && !$getMassages->isEmpty()) {
                $getMassages->map(function($getMassage) use(&$return) {
                    if (!empty($getMassage->massagePrice) && !empty($getMassage->massagePrice->massage) && !empty($getMassage->massagePrice->timing)) {
                        $massage = $getMassage->massagePrice->massage;
                        $timing  = $getMassage->massagePrice->timing;

                        $return[] = [
                            'name'  => $massage->name,
                            'time'  => $timing->time,
                            'image' => $massage->image
                        ];
                    }
                });
            }

            if (!empty($return)) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User pack services found successfully !',
                    'data' => $return
                ]);
            }
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'User pack service not found !',
            'data' => []
        ]);
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $userPack = $this->userPack->find($id);

        if (!empty($userPack)) {
            return $userPack->get();
        }

        return NULL;
    }

    public function errors()
    {}
}
