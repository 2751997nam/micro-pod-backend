<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    protected $table = "log";
    protected $fillable = [
        'actor_type',
        'actor_email',
        'target_type',
        'target_id',
        'event_type',
        'data',
        'created_at'
    ];
    const UPDATED_AT = null;

    const EVENT_TYPE_CREATE = 'CREATE';
    const EVENT_TYPE_UPDATE = 'UPDATE';
    const EVENT_TYPE_STATUS = 'UPDATE_STATUS';
    const EVENT_TYPE_PAYMENT_STATUS = 'EDIT_PAYMENT_STATUS';
    const EVENT_TYPE_PRODUCT_ADVERTISING_STATUS = 'UPDATE_PRODUCT_ADVERTISING_STATUS';
    const EVENT_TYPE_ADVERTISING_STATUS = 'UPDATE_ADVERTISING_STATUS';
    const EVENT_TYPE_UPDATE_ADVERTISING = 'UPDATE_ADVERTISING';
    const EVENT_TYPE_CREATE_ADVERTISING = 'CREATE_ADVERTISING';
    const EVENT_TYPE_UPDATE_FROM_DEFAULT_SKU = 'UPDATE_FROM_DEFAULT_SKU';
}
