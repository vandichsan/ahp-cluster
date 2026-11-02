<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    protected $guarded = [];

    public function subCriteria()
    {
        return $this->belongsToMany(SubCriteria::class, 'alternative_sub_criteria');
    }
}
