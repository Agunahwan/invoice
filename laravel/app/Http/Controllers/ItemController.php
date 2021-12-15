<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Routing\Controller as BaseController;

class ItemController extends BaseController
{
    public function all()
    {
        $data = [
            'data' => Item::with('itemType')->get(),
        ];

        return response()->json($data, 200);
    }
}