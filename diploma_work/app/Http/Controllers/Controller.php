<?php

namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * @OA\Info(
 *      title="Structure API",
 *      version="1.0.0",
 * )
 * @OA\PathItem(path="/api")
 */

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;
}
