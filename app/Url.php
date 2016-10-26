<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    public function stats()
    {
    	return $this->hasMany('\App\UrlStat', 'url_id');
    }
}
