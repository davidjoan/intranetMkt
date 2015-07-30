<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model {

    protected $table = 'cost_centers';

    protected $fillable = ['division_id','code','name','responsible','manager','cost_center_type', 'description'];


}
