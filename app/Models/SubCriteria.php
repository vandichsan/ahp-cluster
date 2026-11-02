<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCriteria extends Model
{
    protected $table = 'sub_criteria';
    protected $guarded = [];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public function alternatives()
    {
        return $this->belongsToMany(Alternative::class, 'alternative_sub_criteria');
    }
}
