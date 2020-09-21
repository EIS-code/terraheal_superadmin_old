<?php

namespace App\Repositories\User\Menu;

use App\Repositories\BaseRepository;
use App\UserMenu;
use App\UserGuide;
use Illuminate\Http\Request;
use DB;

class UserMenuRepository extends BaseRepository
{
    protected $userMenu, $userGuide, $iconPath, $fileSystem;

    public function __construct()
    {
        parent::__construct();
        $this->userMenu   = new UserMenu();
        $this->userGuide  = new userGuide();

        $this->fileSystem = $this->userMenu->fileSystem;
        $this->iconPath   = $this->userMenu->iconPath;
    }

    public function create(array $data)
    {}

    public function all($isApi = false)
    {
        $data = $this->userMenu->all();

        if ($isApi === true) {
            if (!empty($data) && !$data->isEmpty()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User menu found successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'User menu not found !',
                'data' => []
            ]);
        }

        return$data;
    }

    public function getMenuItem(array $data)
    {
        if (empty($data['menu_id'])) {
            return response()->json([
                'code' => 401,
                'msg'  => 'Please provide menu id !'
            ]);
        }

        $menuId = (int)$data['menu_id'];


        if ($menuId == 1) {
            $data = $this->userGuide->all();

            if (!empty($data) && !$data->isEmpty()) {
                return response()->json([
                    'code' => 200,
                    'msg'  => 'User menu item found successfully !',
                    'data' => $data
                ]);
            }

            return response()->json([
                'code' => 200,
                'msg'  => 'User menu item found !',
                'data' => []
            ]);
        }
    }

    public function getWhere($column, $value)
    {
        return $this->userMenu->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->userMenu->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'User menu found successfully !',
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

    public function get(int $id)
    {
        $userMenu = $this->userMenu->find($id);

        if (!empty($userMenu)) {
            return $userMenu->get();
        }

        return NULL;
    }

    public function errors()
    {}

    public function isErrorFree()
    {
        return (empty($this->errorMsg));
    }
}
