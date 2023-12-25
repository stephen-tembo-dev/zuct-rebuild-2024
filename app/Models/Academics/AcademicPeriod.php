<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'code', 'ac_start_date', 'ac_end_date', 'period_type_id'];

    public function period_types(){
        return $this->belongsTo(PeriodType::class,'period_type_id','id');
    }
    public function study_mode(){
        return $this->belongsTo(StudyMode::class,'study_mode_id','id');
    }
    public function academic_period_information(){
        return $this->hasOne(AcademicPeriodInformation::class,'academic_period_id','id');
    }
    public function classes(){
        return $this->hasMany(AcademicPeriodClass::class,'academic_period_id','id');
    }

}
