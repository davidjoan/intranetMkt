<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model {

    protected $table = 'expense_types';

    protected $fillable = ['book_account_id','code','name','description'];

    public function file_formats()
    {
        return $this->belongsToMany('IntranetMkt\Models\FileFormat');
    }
}
