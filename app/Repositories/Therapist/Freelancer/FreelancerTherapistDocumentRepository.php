<?php

namespace App\Repositories\Therapist\Freelancer;

use App\Repositories\BaseRepository;
use App\FreelancerTherapistDocument;
use DB;

class FreelancerTherapistDocumentRepository extends BaseRepository
{
    protected $freelancerTherapistDocument;

    public function __construct()
    {
        parent::__construct();
        $this->freelancerTherapistDocument = new FreelancerTherapistDocument();
    }

    public function create(array $data, int $freelancerTherapipstId)
    {
        $freelancerTherapistDocument = [];
        DB::beginTransaction();

        try {
            $data = (is_array($data) ? $data : [$data]);

            foreach ($data as $row) {
                $row['freelancer_therapist_id'] = $freelancerTherapipstId;
                $validator = $this->freelancerTherapistDocument->validator($row);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                $freelancerTherapistDocument = new FreelancerTherapistDocument();
                $freelancerTherapistDocument->fill($row);
                $freelancerTherapistDocument->save();
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Freelancer therapist documents created successfully !',
            'data' => $freelancerTherapistDocument
        ]);
    }

    public function all()
    {
        return $this->freelancerTherapistDocument->all();
    }

    public function getWhere($column, $value)
    {
        return $this->freelancerTherapistDocument->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->freelancerTherapistDocument->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Freelancer therapist document found successfully !',
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
        $freelancerTherapistDocument = $this->freelancerTherapistDocument->find($id);

        if (!empty($freelancerTherapistDocument)) {
            return $freelancerTherapistDocument->get();
        }

        return NULL;
    }

    public function errors()
    {}
}
