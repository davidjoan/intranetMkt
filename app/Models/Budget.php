<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model {

    protected $table = 'budgets';

    public $timestamps = false;

    protected $fillable = ['division_id','cycle_id','cost_center_id','book_account_id','user_id','amount'];

}
