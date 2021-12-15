<?php

namespace App\Http\Controllers;

use App\Models\InvoiceHeader;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class InvoiceController extends BaseController
{
    public function index()
    {
        return view('invoice');
    }

    public function create()
    {
        $data = array(
            'header' => 'Add Invoice',
            'isView' => 0,
        );
        return view('add_invoice', $data);
    }

    public function data(Request $request)
    {
        $perpage = 10;
        if ($request->get('per_page')) {
            $perpage = $request->get('per_page');
        }
        $unit = InvoiceHeader::paginate($perpage);
        if (isset($request->search)) {
            $filters = [];
            foreach ($request->search as $key => $val) {
                $filter = [$key, 'ILIKE', "%$val%"];
                array_push($filters, $filter);
            }

            $unit = InvoiceHeader::where($filters)->paginate($perpage);
        }

        return response()->json($unit, 200);
    }
}