<?php

namespace App\Repositories\Shop;

use App\Repositories\BaseRepository;
use App\Shop;
use App\ShopFeaturedImage;
use App\ShopGallary;
use App\ShopHour;
use App\ShopCompany;
use App\Massage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use DB;

class ShopRepository extends BaseRepository
{
    protected $shop, $shopFeaturedImage, $shopGallary, $shopHour, $shopCompany, $massage;

    public function __construct()
    {
        parent::__construct();
        $this->shop              = new Shop();
        $this->shopFeaturedImage = new ShopFeaturedImage();
        $this->shopGallary       = new ShopGallary();
        $this->shopHour          = new ShopHour();
        $this->shopCompany       = new ShopCompany();
        $this->massage           = new Massage();
    }

    public function create(array $data)
    {
        $shop = [];
        DB::beginTransaction();

        try {
            $validator = $this->shop->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $shop             = $this->shop;
            $data['shop_password'] = (!empty($data['shop_password']) ? Hash::make($data['shop_password']) : NULL);
            $data['manager_password'] = (!empty($data['manager_password']) ? Hash::make($data['manager_password']) : NULL);

            $shop->fill($data);
            $shop->save();

            $shopId = $shop->id;

            if (!empty($data['featured_image'])) {
                foreach($data['featured_image'] as $featuredImage) {
                    if ($featuredImage instanceof UploadedFile) {
                        $pathInfos = pathinfo($featuredImage->getClientOriginalName());

                        if (!empty($pathInfos['extension'])) {
                            $fileName  = (empty($pathInfos['filename']) ? time() : $pathInfos['filename']) . '_' . time() . '.' . $pathInfos['extension'];
                            $storeFile = $featuredImage->storeAs($this->shopFeaturedImage->storageFolderName, $fileName, $this->shopFeaturedImage->fileSystem);

                            if ($storeFile) {
                                $this->shopFeaturedImage->create(['image' => $fileName, 'shop_id' => $shopId]);
                            }
                        }
                    }
                }
            }

            if (!empty($data['gallery'])) {
                foreach($data['gallery'] as $gallery) {
                    if ($gallery instanceof UploadedFile) {
                        $pathInfos = pathinfo($gallery->getClientOriginalName());

                        if (!empty($pathInfos['extension'])) {
                            $fileName  = (empty($pathInfos['filename']) ? time() : $pathInfos['filename']) . '_' . time() . '.' . $pathInfos['extension'];
                            $storeFile = $gallery->storeAs($this->shopGallary->storageFolderName, $fileName, $this->shopGallary->fileSystem);

                            if ($storeFile) {
                                $this->shopGallary->create(['image' => $fileName, 'shop_id' => $shopId]);
                            }
                        }
                    }
                }
            }

            $sunday = $monday = $tuesday = $wednesday = $thursday = $friday = $saturday = 0;
            if (isset($data['sunday'])) {
                $sunday = $data['sunday'];
            }
            if (isset($data['monday'])) {
                $monday = $data['monday'];
            }
            if (isset($data['tuesday'])) {
                $tuesday = $data['tuesday'];
            }
            if (isset($data['wednesday'])) {
                $wednesday = $data['wednesday'];
            }
            if (isset($data['thursday'])) {
                $thursday = $data['thursday'];
            }
            if (isset($data['friday'])) {
                $friday = $data['friday'];
            }
            if (isset($data['saturday'])) {
                $saturday = $data['saturday'];
            }

            $shopHourData = [
                'sunday'    => $sunday,
                'monday'    => $monday,
                'tuesday'   => $tuesday,
                'wednesday' => $wednesday,
                'thursday'  => $thursday,
                'friday'    => $friday,
                'saturday'  => $saturday,
                'shop_id'   => $shopId
            ];
            $validator = $this->shopHour->validator($shopHourData);

            if (!$validator->fails()) {
                $this->shopHour->create($shopHourData);
            }

        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Shop created successfully !',
            'data' => $shop
        ]);
    }

    public function createLocation(array $data)
    {
        $shop = [];
        DB::beginTransaction();

        try {
            $validator = $this->shop->validatorLocation($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $shop = $this->shop;
            $shop->fill($data);
            $shop->save();

        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Shop location created successfully !',
            'data' => $shop
        ]);
    }

    public function companyCreate(array $data)
    {
        $shop = [];
        DB::beginTransaction();

        try {
            $data['shop_id'] = \Session()->get('shopId');

            $validator = $this->shopCompany->validator($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $shopCompany = $this->shopCompany;
            $shopCompany->fill($data);
            $shopCompany->save();

        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Shop company created successfully !',
            'data' => $shopCompany
        ]);
    }

    public function all()
    {
        return $this->shop->all();
    }

    public function getWhere($column, $value)
    {
        return $this->shop->where($column, $value)->get();
    }

    public function filter(array $data)
    {
        $isFiltered  = false;

        if (count($data) > 0) {
            $isFiltered = (!empty(array_filter($data)));
        }

        $shops = $this->shop::query();

        if ($isFiltered) {

            if (!empty($data['s'])) {
                $s = (string)$data['s'];

                $shops->where($this->shop::getTableName() . '.name', 'LIKE', "%$s%");
            }

            if (!empty($data['c'])) {
                $c = (int)$data['c'];

                $shops->where($this->massage::getTableName() . '.id', 'LIKE', "%$c%");
            }
        }

        $shops = $shops->select(DB::RAW($this->shop::getTableName() . '.*, COUNT(' . $this->massage::getTableName() . '.id) as totalServices'))
                       ->leftJoin($this->massage::getTableName(), $this->shop::getTableName() . '.id', '=', $this->massage::getTableName() . '.shop_id')
                       ->groupBy($this->massage::getTableName() . '.shop_id')
                       ->paginate($this::PAGINATE_RECORDS);

        return $shops;
    }

    public function getMassages()
    {
        return $this->massage::all();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->shop->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Shop found successfully !',
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
        $shop = $this->shop->find($id);

        if (!empty($shop)) {
            return $shop->get();
        }

        return NULL;
    }

    public function errors()
    {}
}
