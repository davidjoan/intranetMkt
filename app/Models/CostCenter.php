<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model {

    protected $table = 'cost_centers';

    public $timestamps = false;

    protected $fillable = ['division_id','code','name','responsible','manager','cost_center_type', 'description'];

    protected function getDateFormat()
    {
        return 'Y-m-d H:i:s.u0';
    }


}
