<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LivestockData extends Model
{
    protected $fillable = ['nfc_uuid', 'temperature', 'humidity', 'pulse_rate', 'loc_latitude', 'loc_longitude'];
}
