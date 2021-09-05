<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Assignuser extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'assignee_users';


    public function getIntegration()
    {
        return $this->belongsTo('App\Models\Integration','integration_id','id');
    }
}
