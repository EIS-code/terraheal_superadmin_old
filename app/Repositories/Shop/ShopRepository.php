<?php

namespace App\Repositories\Shop;

use App\Repositories\BaseRepository;
use App\Shop;
use App\ShopFeaturedImage;
use App\ShopGallary;
use App\ShopHour;
use App\ShopCompany;
use App\ShopDocument;
use App\Massage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use DB;

class ShopRepository extends BaseRepository
{
    protected $shop, $shopFeaturedImage, $shopGallary, $shopHour, $shopCompany, $shopDocument, $massage;

    public function __construct()
    {
        parent::__construct();
        $this->shop              = new Shop();
        $this->shopFeaturedImage = new ShopFeaturedImage();
        $this->shopGallary       = new ShopGallary();
        $this->shopHour          = new ShopHour();
        $this->shopCompany       = new ShopCompany();
        $this->shopDocument      = new ShopDocument();
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
        $shopCompany = [];
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

    public function ownerCreate(array $data)
    {
        $shop = [];
        DB::beginTransaction();

        try {
            $shopId = \Session()->get('shopId');
            $shop   = $this->shop;

            $record = $this->getWhereFirst('id', $shopId);

            if (empty($record)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Shop not found!'
                ]);
            }

            $data['shop_id'] = $shopId;

            $validator = $this->shop->validatorOwner($data);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 401,
                    'msg'  => $validator->errors()->first()
                ]);
            }

            $shop = $shop->where('id', $shopId)->update([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'owner_email' => $data['owner_email'],
                'financial_situation' => $data['financial_situation'],
                'owner_mobile_number' => $data['owner_mobile_number'],
                'owner_mobile_number_alternative' => $data['owner_mobile_number_alternative']
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Shop owner created successfully !',
            'data' => $this->getWhereFirst('id', $shopId)
        ]);
    }

    public function documentCreate(array $data)
    {
        $shop = [];
        DB::beginTransaction();

        try {
            $shopId = \Session()->get('shopId');
            $shop   = $this->shop;

            $record = $this->getWhereFirst('id', $shopId);

            if (empty($record)) {
                return response()->json([
                    'code' => 401,
                    'msg'  => 'Shop not found!'
                ]);
            }

            $data['shop_id'] = $shopId;

            $isCreated = false;

            if (!empty($data['franchise_contact'])) {
                $franchiseContact = $data['franchise_contact'];
                if ($franchiseContact instanceof UploadedFile) {
                    $pathInfos = pathinfo($franchiseContact->getClientOriginalName());

                    if (!empty($pathInfos['extension'])) {
                        $fileName  = (empty($pathInfos['filename']) ? time() : $pathInfos['filename']) . '_' . time() . '.' . $pathInfos['extension'];
                        $storeFile = $franchiseContact->storeAs($this->shopDocument->storageFolderNameFranchise, $fileName, $this->shopDocument->fileSystem);

                        if ($storeFile) {
                            $this->shopDocument->create(['franchise_contact' => $fileName, 'shop_id' => $shopId]);

                            $isCreated = true;
                        }
                    }
                }
            }

            if (!empty($data['id_passport'])) {
                $idPassport = $data['id_passport'];
                if ($idPassport instanceof UploadedFile) {
                    $pathInfos = pathinfo($idPassport->getClientOriginalName());

                    if (!empty($pathInfos['extension'])) {
                        $fileName  = (empty($pathInfos['filename']) ? time() : $pathInfos['filename']) . '_' . time() . '.' . $pathInfos['extension'];
                        $storeFile = $idPassport->storeAs($this->shopDocument->storageFolderNameIdPassport, $fileName, $this->shopDocument->fileSystem);

                        if ($storeFile) {
                            $this->shopDocument->create(['id_passport' => $fileName, 'shop_id' => $shopId]);

                            $isCreated = true;
                        }
                    }
                }
            }

            if (!empty($data['registration'])) {
                $registration = $data['registration'];
                if ($registration instanceof UploadedFile) {
                    $pathInfos = pathinfo($registration->getClientOriginalName());

                    if (!empty($pathInfos['extension'])) {
                        $fileName  = (empty($pathInfos['filename']) ? time() : $pathInfos['filename']) . '_' . time() . '.' . $pathInfos['extension'];
                        $storeFile = $registration->storeAs($this->shopDocument->storageFolderNameRegistration, $fileName, $this->shopDocument->fileSystem);

                        if ($storeFile) {
                            $this->shopDocument->create(['registration' => $fileName, 'shop_id' => $shopId]);

                            $isCreated = true;
                        }
                    }
                }
            }

        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        if ($isCreated) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Shop document created successfully !',
                'data' => $this->getWhereFirst('id', $shopId)
            ]);
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Shop document not created ! Please try again.',
            'data' => $this->getWhereFirst('id', $shopId)
        ]);
    }

    public function getInfo(int $shopId)
    {
        $shop = $this->shop->find($shopId);

        $data = [
            'massages' => 0,
            'totalBookingsImc' => 0,
            'totalBookingsHv' => 0
        ];

        if (!empty($shop)) {
            $data['massages'] = $shop->massages->count();

            $bookings = $shop->bookingsImc;
            if (!empty($bookings) && !$bookings->isEmpty()) {
                foreach ($bookings as $booking) {
                    $data['totalBookingsImc'] += $booking->bookingInfo->count();
                }
            }
            $bookings = $shop->bookingsHv;
            if (!empty($bookings) && !$bookings->isEmpty()) {
                foreach ($bookings as $booking) {
                    $data['totalBookingsHv'] += $booking->bookingInfo->count();
                }
            }
        }

        return response()->json([
            'code' => 200,
            'msg'  => 'Shop info found successfully !',
            'data' => $data
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
