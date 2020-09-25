<?php

namespace App\Repositories\User\Pack;

use App\Repositories\BaseRepository;
use App\UserPackGift;
use DB;

class UserPackGiftRepository extends BaseRepository
{
    protected $userPackGift;

    public function __construct()
    {
        parent::__construct();
        $this->userPackGift = new UserPackGift();
    }

    public function create(array $data)
    {
        $userPackGift = [];
        DB::beginTransaction();

        try {
            if (!empty($data['preference_email_date'])) {
                $emailDate = $data['preference_email_date'] = date("Y-m-d", ($data['preference_email_date'] / 1000));
            }

            $validator = $this->userPackGift->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $userPackGift = $this->userPackGift;

            $userPackGift->fill($data);
            $save = $userPackGift->save();

            if ($save) {
                $today     = strtotime(date('Y-m-d'));
                $emailDate = strtotime($emailDate);

                if ($today == $emailDate) {
                    // Send Email.
                } else {
                    // Set console command for send email for the future date.
                }
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User pack gift created successfully !',
            'data' => $userPackGift
        ]);
    }

    public function all()
    {
        return $this->userPackGift->all();
    }

    public function getWhere($column, $value, $isApi = false)
    {
        $data = $this->userPackGift->where($column, $value)->get();

        if ($isApi === true) {
            if (!empty($data) && !$data->isEmpty()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User pack gifts found successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'User pack gift not found !',
                'data' => []
            ]);
        }

        return $data;
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $userPackGift = $this->userPackGift->find($id);

        if (!empty($userPackGift)) {
            return $userPackGift->get();
        }

        return NULL;
    }

    public function errors()
    {}
}
