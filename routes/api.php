<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Webhooks\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json(
        [ 
            "success" => true,
            "data" => [
                "user" => [
                    "name" => $request->user()->name,
                    "email" => $request->user()->email,
                ]
            ]
        ]
    );
});

Route::apiResource('/webhooks',WebhookController::class)->middleware('auth:sanctum');
