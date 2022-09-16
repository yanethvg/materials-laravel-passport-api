<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'description','stock_minim','is_active','unit_measure_id','category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id', 'id');
    }
    public function measure()
    {
        return $this->belongsTo(UnitMeasure::class,'unit_measure_id', 'id');
    }
    public function scopeCategory($query, $category)
    {
        if ($category)
            return $query->whereHas('category', function ($query) use ($category) {
                $query->where('name', 'ilike', "%$category%");
            });
    }
    public function scopeMeasure($query, $measure)
    {
        if ($measure)
            return $query->whereHas('measure', function ($query) use ($measure) {
                $query->where('name', 'ilike', "%$measure%");
            });
    }
    public function scopeFilter($query, $q)
    {
        if ($q)
            $query->where('name', 'ILIKE', "%$q%")->orWhere('description', 'ILIKE', "%$q%");
    }
}
