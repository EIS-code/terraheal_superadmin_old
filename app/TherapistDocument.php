<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TherapistDocument extends Model
{
    protected $fillable = [
        'type',
        'file_name',
        'therapist_id'
    ];

    public $directory = 'therapist/document';

    const TYPE_ADDRESS_PROOF  = '1';
    const TYPE_IDENTITY_PROOF = '2';
    const TYPE_INSURANCE      = '3';

    public $documentTypes = [];

    public function validator(array $data)
    {
        return Validator::make($data, [
            'type'         => ['required', 'in:1,2,3'],
            'file_name'    => ['required', 'string', 'max:255'],
            'therapist_id' => ['required', 'integer']
        ]);
    }
    
    public function validateMimeTypes($request)
    {
        return Validator::make($request->all(), [
            'file.*' => 'mimes:jpeg,png,jpg,doc,docx,pdf',
        ], [
            'file.*' => 'Please select proper file. The file must be a file of type: jpeg, png, jpg, doc, docx, pdf.'
        ]);
    }
}
