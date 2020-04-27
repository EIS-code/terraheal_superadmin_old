<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\TherapistDocument;
use DB;

class TherapistDocumentRepository extends BaseRepository
{
    protected $therapistDocument;

    public function __construct()
    {
        parent::__construct();
        $this->therapistDocument = new therapistDocument();
    }

    public function create(array $data, int $therapipstId)
    {
        $therapistDocument = [];
        DB::beginTransaction();

        try {
            $data = (is_array($data) ? $data : [$data]);

            foreach ($data as $row) {
                $row['therapist_id'] = $therapipstId;
                $validator = $this->therapistDocument->validator($row);
                if ($validator->fails()) {
                    return response()->json([
                        'code' => 401,
                        'msg'  => $validator->errors()->first()
                    ]);
                }

                $therapistDocument = new therapistDocument();
                $therapistDocument->fill($row);
                $therapistDocument->save();
            }
        } catch(Exception $e) {
            DB::rollBack();
            // throw $e;
        }

        DB::commit();

        return response()->json([
            'code' => 200,
            'msg'  => 'Therapist documents created successfully !',
            'data' => $therapistDocument
        ]);
    }

    public function all()
    {
        return $this->therapistDocument->all();
    }

    public function getWhere($column, $value)
    {
        return $this->therapistDocument->where($column, $value)->get();
    }

    public function getWhereFirst($column, $value, $isApi = false)
    {
        $data = $this->therapistDocument->where($column, $value)->first();

        if ($isApi === true) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Therapist document found successfully !',
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
        $therapistDocument = $this->therapistDocument->find($id);

        if (!empty($therapistDocument)) {
            return $therapistDocument->get();
        }

        return NULL;
    }

    public function errors()
    {}
}
