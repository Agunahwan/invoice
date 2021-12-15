<?php

namespace App\Http\Controllers;

use App\Models\InvoiceHeader;
use App\Models\Setting;
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
        // Get new ID for Invoice
        $invoice = new InvoiceHeader();
        $latestInvoice = InvoiceHeader::latest()->get();
        if (count($latestInvoice) > 0) {
            $invoice->id = $latestInvoice->id + 1;
        } else {
            $invoice->id = 1;
        }
        $formattedId = substr('0000' . $invoice->id, -4);

        // Get value of tax
        $tax = 0;
        $setting = Setting::where('key', 'TAX')->get();

        if (count($setting)) {
            $tax = $setting[0]->value;
        }

        $data = array(
            'header' => 'Add Invoice',
            'isView' => 0,
            'invoice' => $invoice,
            'formattedId' => $formattedId,
            'tax' => $tax,
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