<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model {

    protected $table = 'budgets';

    public $timestamps = false;

    protected $fillable = ['division_id','cycle_id','cost_center_id','book_account_id','user_id','amount'];

    public function user()
    {
        return $this->belongsTo('IntranetMkt\User');
    }

    public function cycle()
    {
        return $this->belongsTo('IntranetMkt\Models\Cycle');
    }

    public function division()
    {
        return $this->belongsTo('IntranetMkt\Models\Division');
    }

    public function cost_center()
    {
        return $this->belongsTo('IntranetMkt\Models\CostCenter');
    }

    public function book_account()
    {
        return $this->belongsTo('IntranetMkt\Models\BookAccount');
    }

}
