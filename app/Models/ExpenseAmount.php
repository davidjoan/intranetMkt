<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseAmount extends Model {

    protected $table = 'expense_amounts';

    public $timestamps = false;

    protected $fillable = ['cost_center_id','expense_id', 'porcentaje', 'amount'];


    public function expense()
    {
        return $this->belongsTo('IntranetMkt\Models\Expense');
    }

    public function cost_center()
    {
        return $this->belongsTo('IntranetMkt\Models\CostCenter');
    }

}
