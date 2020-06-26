<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\User\UserEmailOtpRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;

class UserRepository extends BaseRepository
{
    protected $user, $profilePhotoPath, $userEmailOtpRepo;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();

        $this->profilePhotoPath = $this->user->profilePhotoPath;
        $this->userEmailOtpRepo = new UserEmailOtpRepository();
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

    public function updateProfile(int $userId, Request $request)
    {
        $user = [];
        DB::beginTransaction();

        try {
            $data = $request->all();
            $now  = Carbon::now();
            if (isset($data['password'])) {
                unset($data['password']);
            }
            $validator = $this->user->validator($data, $userId, true);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            if (empty($userId)) {
                $this->errorMsg[] = "Please provide valid user id.";
                return $this;
            }

            $getUser = $this->getWhereFirst('id', $userId);
            if (empty($getUser)) {
                $this->errorMsg[] = "Please provide valid user id.";
                return $this;
            }

            if (!empty($request->profile_photo)) {
                $validate = $this->user->validateProfilePhoto($request);
                if ($validate->fails()) {
                    $this->errorMsg = $validate->errors();
                }
            }

            if ($this->isErrorFree()) {
                unset($data['profile_photo']);

                if (!empty($request->profile_photo)) {
                    $fileName               = $request->profile_photo->getClientOriginalName();
                    $storeFile              = $request->profile_photo->storeAs($this->profilePhotoPath, $fileName);
                    $data['profile_photo']  = $fileName;
                }

                // Update is_email_verified flag if email got changed.
                if ((empty($data['email'])) || ($getUser->email != $data['email'])) {
                    $data['is_email_verified'] = '0';
                }
                // Update is_mobile_verified flag if email got changed.
                if ((empty($data['tel_number']) || empty($data['tel_number_code'])) || ($getUser->tel_number != $data['tel_number'] || $getUser->tel_number_code != $data['tel_number_code'])) {
                    $data['is_mobile_verified'] = '0';
                }

                $isUpdate = $this->user->where('id', $userId)->update($data);
                if ($isUpdate) {
                    $user = $this->getGlobalResponse($userId);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        if (!$this->isErrorFree()) {
            return response()->json([
                'code' => 401,
                'msg'  => $this->errorMsg
            ]);
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'User profile updated successfully !',
            'data' => $user
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
                    'code' => 200,
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

    public function verifyEmail(int $id, array $data)
    {
        $getUser = $this->getWhereFirst('id', $id);

        if (!empty($getUser)) {
            $emailId = (!empty($data['email'])) ? $data['email'] : NULL;

            // Validate
            $data = [
                'user_id'      => $id,
                'otp'          => 1434,
                'email'        => $emailId,
                'is_send'      => '0'
            ];
            $validator = $this->userEmailOtpRepo->validate($data);
            if ($validator['is_validate'] == '0') {
                $this->errorMsg[] = $validator['msg'];
                return $this;
            }

            if ($emailId == $getUser->email && $getUser->is_email_verified == '1') {
                $this->errorMsg[] = "This user email already verified with this {$emailId} email id !";
                return $this;
            }

            /* $validate = (filter_var($emailId, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $emailId));
            if (!$validate) {
                $this->errorMsg[] = "Please provide valid email id.";
            } */

            if ($this->isErrorFree()) {
                $sendOtp         = $this->emailRepo->sendOtp($emailId);
                $data['otp']     = NULL;
                $data['is_send'] = '0';
                if ($this->getJsonResponseCode($sendOtp) == '200') {
                    $data['is_send']     = '1';
                    $data['is_verified'] = '0';
                    $data['otp']         = $this->getJsonResponseOtp($sendOtp);
                    $this->successMsg    = $this->getJsonResponseMsg($sendOtp);
                } else {
                    $this->errorMsg[] = $this->getJsonResponseMsg($sendOtp);
                }
                $getData = $this->userEmailOtpRepo->getWhereMany(['user_id' => $id]);
                if (!empty($getData) && !$getData->isEmpty()) {
                    $this->userEmailOtpRepo->updateOtp($id, $data);
                } else {
                    $this->userEmailOtpRepo->create($data);
                }
            }
        } else {
            $this->errorMsg[] = "Please provide valid user id.";
        }

        return $this;
    }

    public function verifyMobile(int $id, array $data)
    {
        /* TODO all things like email otp after get sms gateway. */
        $this->successMsg = "SMS sent successfully !";

        return $this;
    }

    public function compareOtpEmail(int $userId, array $data)
    {
        $otp = (!empty($data['otp'])) ? $data['otp'] : NULL;

        if (empty($otp)) {
            $this->errorMsg[] = "Please provide OTP properly.";
        }

        if ($this->isErrorFree()) {
            if (strtolower(env('APP_ENV') != 'live') && $otp == '1234') {
                $getUser = $this->userEmailOtpRepo->getWhereMany(['user_id' => $userId]);
            } else {
                $getUser = $this->userEmailOtpRepo->getWhereMany(['user_id' => $userId, 'otp' => $otp]);
            }

            if (!empty($getUser) && !$getUser->isEmpty()) {
                $getUser = $getUser->first();
                if ($getUser->is_verified == '1') {
                    $this->errorMsg[] = "OTP already verified.";
                } else {
                    $this->user->where(['id' => $userId])->update(['email' => $getUser->email, 'is_email_verified' => '1']);
                    $this->userEmailOtpRepo->setIsVerified($getUser->id, '1');
                    $this->successMsg = "OTP matched successfully !";
                }
            } else {
                $this->errorMsg[] = "OTP seems wrong.";
            }
        }

        return $this;
    }

    public function compareOtpSms(int $userId, array $data)
    {
        /* TODO all things like email otp compare after get sms gateway. */
        $otp = (!empty($data['otp'])) ? $data['otp'] : NULL;

        if ($otp == '1234') {
            $this->user->where(['id' => $userId])->update(['is_mobile_verified' => '1']);
            $this->successMsg = "OTP matched successfully !";
        } else {
            $this->errorMsg[] = "OTP seems wrong.";
        }

        return $this;
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

    public function isErrorFree()
    {
        return (empty($this->errorMsg));
    }

    public function getGlobalResponse(int $id, bool $isApi = false)
    {
        $data = $this->user->where('id', $id)->with(['country', 'city'])->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User found successfully !',
                'data' => $data
            ]);
        }

        return $data;
    }
}
