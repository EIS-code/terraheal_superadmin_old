<?php

namespace App\Repositories\Therapist;

use App\Repositories\BaseRepository;
use App\TherapistDocument;
use App\Repositories\Therapist\TherapistRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use DB;

class TherapistDocumentRepository extends BaseRepository
{
    protected $therapistDocument, $therapist, $directory;

    public $isFreelancer = '0', $errorMsg, $successMsg;

    public function __construct()
    {
        parent::__construct();
        $this->therapistDocument = new therapistDocument();

        $this->directory = $this->therapistDocument->directory;
    }

    public function create(int $therapistId, Request $request)
    {
        $therapistDocument = [];
        $this->therapist   = new TherapistRepository();
        DB::beginTransaction();

        try {
            /* $data = (is_array($data) ? $data : [$data]);

            foreach ($data as $row) {
                $row['therapist_id'] = $therapistId;
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
            } */
            $data = $request->all();
\Log::info(['$data' => $data]);

            if (empty($therapistId)) {
                $this->errorMsg[] = "Please provide valid therapist id.";
                return $this;
            }

            $getTherapist = $this->therapist->getWhereFirst('id', $therapistId);
            if (empty($getTherapist)) {
                $this->errorMsg[] = "Therapist couldn't found with given therapist id.";
                return $this;
            }

            if (empty($data['type'])) {
                $this->errorMsg[] = "Please provide document type. 1: Address Proof, 2: Identity Proof, 3: Insurance";
            }

            if (empty($data['file']) || !($request->hasFile('file'))) {
                $this->errorMsg[] = "Please add document.";
            } else {
                $validator = $this->therapistDocument->validateMimeTypes($request);
                if ($validator->fails()) {
                    $this->errorMsg = $validator->errors();
                }
            }
\Log::info(['$isErrorFree' => $this->isErrorFree()]);
            if ($this->isErrorFree()) {
                unset($data['file']);
                $data['therapist_id'] = $therapistId;
                \Log::info(['$files' => $request->file]);
                foreach ($request->file as $file) {
                    $fileName  = $file->getClientOriginalName();
                    $storeFile = $file->storeAs($this->directory, $fileName);
                    \Log::info(['$storeFile' => $storeFile]);

                    if ($storeFile) {
                        $data['file_name']    = $fileName;
                        $therapistDocument    = new therapistDocument();
                        $therapistDocument->fill($data);
                        $therapistDocument->save();
                    }
                }

                // Check all documents uploaded.
                if ($this->checkAllDocumentsUploaded($therapistId)) {
                    $this->therapist->isDocumentVerified($therapistId, '1');
                }
            }
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
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

    public function isErrorFree()
    {
        return (empty($this->errorMsg));
    }

    public function checkAllDocumentsUploaded($therapistId)
    {
        $isUploadedAll = false;

        $getDocuments = $this->getWhere('therapist_id', $therapistId);

        if (!empty($getDocuments) && !$getDocuments->isEmpty()) {
            $documentTypes = $this->therapistDocument->documentTypes;
            $uploadedType  = $getDocuments->pluck('type')->unique();

            foreach ($uploadedType as $type) {
                if (array_key_exists($type, $documentTypes)) {
                    unset($documentTypes[$type]);
                }
            }

            $isUploadedAll = ((empty($documentTypes)) ? true : false);
        }

        return $isUploadedAll;
    }
}
