<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model {

    protected $table = 'expenses';

    protected $fillable = ['expense_type_id', 'user_id','division_id','application_date','code','name',
                            'description','approval_a','approval_b','approval_c','approval_d','approval_e',
                            'total_amount','estimated_amount','active'];

    public function expense_type()
    {
        return $this->belongsTo('IntranetMkt\Models\ExpenseType');
    }

    public function user()
    {
        return $this->belongsTo('IntranetMkt\User');
    }

    public function division()
    {
        return $this->belongsTo('IntranetMkt\Models\Division');
    }

    public function buy_orders()
    {
        return $this->hasMany('IntranetMkt\Models\BuyOrder', 'expense_id','id');
    }

    public function entertainments()
    {
        return $this->hasMany('IntranetMkt\Models\Entertainment', 'expense_id','id');
    }

    public function medical_campaigns()
    {
        return $this->hasMany('IntranetMkt\Models\MedicalCampaign', 'expense_id','id');
    }

    public function request_attentions()
    {
        return $this->hasMany('IntranetMkt\Models\RequestAttention', 'expense_id','id');
    }

    public function sponsorship_application()
    {
        return $this->hasOne('IntranetMkt\Models\SponsorshipApplication', 'expense_id','id');
    }

    public function expense_details()
    {
        return $this->hasMany('IntranetMkt\Models\ExpenseDetail', 'expense_id','id');
    }

    public function expense_amounts()
    {
        return $this->hasMany('IntranetMkt\Models\ExpenseAmount', 'expense_id','id');
    }



}
