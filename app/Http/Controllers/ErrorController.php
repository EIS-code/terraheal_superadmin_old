<?php

namespace App\Http\Controllers;

class ErrorController extends BaseController
{
    public function error()
    {
        error();
    }
}
