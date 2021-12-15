<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Routing\Controller as BaseController;

class ClientController extends BaseController
{
    public function all()
    {
        $data = [
            'data' => Client::all(),
        ];

        return response()->json($data, 200);
    }
}