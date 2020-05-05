<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\User;
use Illuminate\Support\Facades\Hash;
use DB;

class UserRepository extends BaseRepository
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    public function create(array $data)
    {
        $user = [];
        DB::beginTransaction();

        try {
            $validator = $this->user->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $user             = $this->user;
            $data['password'] = (!empty($data['password']) ? Hash::make($data['password']) : NULL);
            $user->fill($data);
            $user->save();

            /*$user->name            = $data['name'];
            $user->dob             = (!empty($data['dob']) ? $data['dob'] : NULL);
            $user->email           = $data['email'];
            $user->tel_number      = (!empty($data['tel_number']) ? $data['tel_number'] : NULL);
            $user->nif             = (!empty($data['nif']) ? $data['nif'] : NULL);
            $user->address         = (!empty($data['address']) ? $data['address'] : NULL);
            $user->avatar          = (!empty($data['avatar']) ? $data['avatar'] : NULL);
            $user->avatar_original = (!empty($data['avatar_original']) ? $data['avatar_original'] : NULL);
            $user->oauth_uid       = (!empty($data['oauth_uid']) ? $data['oauth_uid'] : NULL);
            $user->oauth_provider  = (!empty($data['oauth_provider']) && in_array($data['oauth_provider'], User::$oauthProviders) ? $data['oauth_provider'] : NULL);
            $user->password        = (!empty($data['password']) ? Hash::make($data['password']) : NULL);
            $user->country_id      = (!empty($data['country_id']) ? $data['country_id'] : NULL);
            $user->shop_id        = $data['shop_id'];

            // $user->fill($data);
            $user->save();*/
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'User created successfully !',
            'data' => $user
        ]);
    }

    public function all()
    {
        return $this->user->all();
    }

    public function getWhere($column, $value)
    {
        return $this->user->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->user->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }

    public function update(int $id, array $data)
    {
        $update   = false;
        $findUser = $this->get($id);

        if (!empty($findUser) && !$findUser->isEmpty()) {
            DB::beginTransaction();

            try {
                if (isset($data['password'])) {
                    unset($data['password']);
                }
                $validator = $this->user->validator($data, $id, true);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                $update = $this->user->where('id', $id)->update($data);
            } catch(Exception $e) {
                DB::rollBack();
                // throw $e;
            }

            if ($update) {
                DB::commit();
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User updated successfully !'
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Something went wrong.'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'User not found.'
        ]);
    }

    public function signIn(array $data)
    {
        $email    = (!empty($data['email'])) ? $data['email'] : NULL;
        $password = (!empty($data['password'])) ? $data['password'] : NULL;

        if (empty($email)) {
            return response()->json([
                'code' => 401,
                'msg'  => 'Please provide email properly.'
            ]);
        } elseif (empty($password)) {
            return response()->json([
                'code' => 401,
                'msg'  => 'Please provide password properly.'
            ]);
        }

        if (!empty($email) && !empty($password)) {
            $getUser = $this->getWhereFirst('email', $email);

            if (!empty($getUser) && Hash::check($password, $getUser->password)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'User found successfully !',
                    'data' => $getUser
                ]);
            } else {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'User email or password seems wrong.'
                ]);
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Something went wrong.'
        ]);
    }

    public function delete(int $id)
    {}

    public function deleteWhere($column, $value)
    {}

    public function get(int $id)
    {
        $user = $this->user->find($id);

        if (!empty($user)) {
            return $user->get();
        }

        return NULL;
    }

    public function errors()
    {}
}
