<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_date', 'updated_date', 'created_by', 'updated_by',
    ];
}