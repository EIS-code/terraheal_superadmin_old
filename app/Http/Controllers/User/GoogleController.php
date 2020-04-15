<?php

namespace App\Http\Controllers\User;

use Socialite;
use App\Http\Controllers\BaseController;
use App\User;
use Exception;

class GoogleController extends BaseController
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->userRepo;
    }

    /**
     * Create a redirect method to google api.
     *
     * @return void
    */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Return a callback method from google api.
     *
     * @return callback URL from google
     */
    public function callback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

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
