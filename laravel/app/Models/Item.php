<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'item_type_id',
        'description',
        'unit_price',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_date', 'updated_date', 'created_by', 'updated_by',
    ];

    public function itemType()
    {
        return $this->hasOne(ItemType::class, 'id', 'item_type_id');
    }
}