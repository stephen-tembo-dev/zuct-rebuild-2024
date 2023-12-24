<?php

namespace App\Models\Users;

use App\Models\Profile\Relationship;
use App\Models\Residency\Country;
use App\Models\Residency\Province;
use App\Models\Residency\Town;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNextOfKin extends Model
{
    use HasFactory;

    protected $fillable = ['full_name', 'telephone', 'mobile', 'relationship_id', 'town_id', 'province_id', 'country_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function relationship()
    {
        return $this->belongsTo(Relationship::class,'relationship_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
    public function town()
    {
        return $this->belongsTo(Town::class,'town_id');
    }
    public function province()
    {
        return $this->belongsTo(Province::class,'province_id');
    }
}
