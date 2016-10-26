<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlStat extends Model
{
    protected $fillable = ['platform', 'browser', 'ip', 'country_name', 'country_code', 'http_referer'];
}
