<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\InvoiceDetail;
use App\Models\InvoiceHeader;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

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
            $invoice->id = $latestInvoice[0]->id + 1;
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

        // Get Company Id
        $company = Company::where('id', 1)->get();
        if (count($company)) {
            $companyId = $company[0]->id;
        }

        $data = array(
            'header' => 'Add Invoice',
            'isView' => 0,
            'invoice' => $invoice,
            'formattedId' => $formattedId,
            'tax' => $tax,
            'companyId' => $companyId,
        );

        return view('add_invoice', $data);
    }

    public function data(Request $request)
    {
        $perpage = 10;
        if ($request->get('per_page')) {
            $perpage = $request->get('per_page');
        }
        $result = [];
        $totalData = InvoiceHeader::count();
        $invoices = InvoiceHeader::paginate($perpage);
        foreach ($invoices as $key => $invoice) {
            $invoice->id = substr('0000' . $invoice->id, -4);

            array_push($result, $invoice);
        }
        // if (isset($request->search)) {
        //     $filters = [];
        //     foreach ($request->search as $key => $val) {
        //         $filter = [$key, 'ILIKE', "%$val%"];
        //         array_push($filters, $filter);
        //     }

        //     $unit = InvoiceHeader::where($filters)->paginate($perpage);
        // }

        $response = array(
            'data' => $result,
            'recordsFiltered' => count($result),
            'recordsTotal' => $totalData,
        );
        return response()->json($response, 200);
    }

    public function all()
    {
        $data = [
            'data' => InvoiceHeader::with('invoiceDetail')->get(),
        ];

        return response()->json($data, 200);
    }

    public function get($id)
    {
        $invoice = InvoiceHeader::with('invoiceDetail')->find($id);

        if ($invoice) {
            $data = [
                'data' => $invoice,
            ];
        } else {
            $data = [
                'data' => null,
                'message' => 'Data not found',
            ];
        }
        return response()->json($data);
    }

    public function save(Request $request)
    {
        try {
            // Populate data for Invoice Header
            $data = new InvoiceHeader();
            $data->company_id = $request->company_id;
            $data->client_id = $request->client_id;
            $data->issue_date = $request->issue_date;
            $data->due_date = $request->due_date;
            $data->subject = $request->subject;
            $data->subtotal = $request->subtotal;
            $data->tax = $request->tax;
            $data->total_payments = $request->total_payments;
            $data->payments = $request->payments;
            $data->amount_due = $request->amount_due;
            $data->is_paid = $request->is_paid;
            $data->created_by = 0;
            $data->updated_by = 0;

            // Populate data for Invoice Detail
            $details = [];
            foreach ($request->items as $key => $detailItem) {
                $detail = new InvoiceDetail();
                $detail->item_id = $detailItem['item_id'];
                $detail->quantity = $detailItem['quantity'];
                $detail->amount = $detailItem['unit_price'];
                $detail->created_by = 0;
                $detail->updated_by = 0;

                array_push($details, $detail);
            }

            DB::transaction(function () use ($data, $details) {
                $data->save();

                foreach ($details as $key => $detail) {
                    /*
                     * insert new record for invoice detail
                     */
                    $detail->invoice_header_id = $data->id;
                    $detail->save();
                }
            });

            $result = array(
                'is_success' => true,
                'data' => $data,
            );

            return response()->json($result, 201);
        } catch (Exception $ex) {
            $result = array(
                'is_success' => false,
                'error_messsage' => $ex,
            );
            return response()->json($result, 500);
        }
    }

    public function edit($id)
    {
        try {
            $invoice = InvoiceHeader::find($id);
            $invoiceDetail = InvoiceDetail::with('item')->where('invoice_header_id', $id)->get();
            $formattedId = substr('0000' . $invoice->id, -4);

            // Get value of tax
            $tax = 0;
            $setting = Setting::where('key', 'TAX')->get();
            if (count($setting)) {
                $tax = $setting[0]->value;
            }

            // Get Company Id
            $companyId = $invoice->company_id;

            $data = array(
                'header' => 'Edit Invoice',
                'isView' => 0,
                'invoice' => $invoice,
                'invoice_detail' => $invoiceDetail,
                'formattedId' => $formattedId,
                'tax' => $tax,
                'companyId' => $companyId,
            );
            // return json_encode($data);
            return view('add_invoice', $data);
        } catch (Exception $ex) {
            $result = array(
                'is_success' => false,
                'error_messsage' => $ex,
            );
            return response()->json($result, 500);
        }
    }

    public function delete($id)
    {
        try {
            $invoice = InvoiceHeader::find($id);

            DB::transaction(function () use ($invoice) {
                // Delete all detail data
                InvoiceDetail::where('invoice_header_id', $invoice->id)->each(function ($detail, $key) {
                    $detail->delete();
                });

                $invoice->delete();
            });

            $result = array(
                'is_success' => true,
                'data' => $invoice,
            );

            return response()->json($result, 200);
        } catch (Exception $ex) {
            $result = array(
                'is_success' => false,
                'error_messsage' => $ex,
            );
            return response()->json($result, 500);
        }
    }
}