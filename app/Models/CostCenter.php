<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model {

    protected $table = 'cost_centers';

    protected $fillable = ['code','name', 'description'];


}
