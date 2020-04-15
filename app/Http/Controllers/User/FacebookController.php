<?php

namespace App\Http\Controllers\User;

use Socialite;
use App\Http\Controllers\BaseController;
use App\User;
use Exception;

class FacebookController extends BaseController
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->userRepo;
    }

    /**
     * Create a redirect method to facebook api.
     *
     * @return void
    */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function callback()
    {
        try {
            $user = Socialite::driver('facebook')->stateless()->user();

            // check if they're an existing user
            $existingUser = $this->user->getWhereFirst('email', $user->email);

            if (empty($existingUser)) {
                $data = [
                    'name'            => $user->getName(),
                    'email'           => $user->getEmail(),
                    'avatar'          => $user->getAvatar(),
                    'avatar_original' => $user->avatar_original,
                    'oauth_uid'       => $user->getId(),
                    'oauth_provider'  => 2
                ];
                $this->user->create($data);
            }

            return response()->json([
                'code' => 200
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'msg'  => $e->getMessage()
            ]);
        }
    }
}
