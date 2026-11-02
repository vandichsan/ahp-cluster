<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criteria';
    protected $guarded = [];

    public function subCriteria()
    {
        return $this->hasMany(SubCriteria::class);
    }

    public function syncSubCriteriaWeights()
    {
        $subs = $this->subCriteria;
        if ($subs->isEmpty()) {
            return;
        }

        $allHaveLocal = $subs->every(fn($s) => $s->local_weight > 0);

        if ($allHaveLocal) {
            foreach ($subs as $s) {
                $s->update([
                    'global_weight' => ($this->weight ?? 0) * $s->local_weight
                ]);
            }
        } else {
            $count = $subs->count();
            $equalLocal = 1.0 / $count;
            $equalGlobal = ($this->weight ?? 0) / $count;
            foreach ($subs as $s) {
                $s->update([
                    'local_weight' => $equalLocal,
                    'global_weight' => $equalGlobal
                ]);
            }
        }
    }
}
