<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseDetail extends Model {

    protected $table = 'expense_details';

    public $timestamps = false;

    protected $fillable = ['file_format_id','expense_id', 'filename','mime','original_filename'];


    public function expense()
    {
        return $this->belongsTo('IntranetMkt\Models\Expense');
    }

    public function file_format()
    {
        return $this->belongsTo('IntranetMkt\Models\FileFormat');
    }

}
