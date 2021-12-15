<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceHeader extends Model
{
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoice_header';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_id',
        'client_id',
        'issue_date',
        'due_date',
        'subject',
        'subtotal',
        'tax',
        'total_payments',
        'payments',
        'amount_due',
        'is_paid',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_date', 'updated_date', 'created_by', 'updated_by',
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function invoiceDetail()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_header_id', 'id');
    }
}