<?php

namespace app\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\Service;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
