<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoice_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'invoice_header_id',
        'item_id',
        'quantity',
        'amount',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_date', 'updated_date', 'created_by', 'updated_by',
    ];

    public function invoiceHeader()
    {
        return $this->hasOne(InvoiceHeader::class, 'id', 'invoice_header_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}