<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class BuyOrder extends Model {

    protected $table = 'buy_orders';

    public $timestamps = false;

    protected $fillable = ['file_format_id','expense_id', 'code','delivery_date','book_account',
                            'inventory','active','expenditure','quantity','price_unit','estimated_value',
                            'description','destination'];

    public function expense()
    {
        return $this->belongsTo('IntranetMkt\Models\Expense');
    }

    public function file_format()
    {
        return $this->belongsTo('IntranetMkt\Models\FileFormat');
    }

}
