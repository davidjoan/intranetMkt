<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class FileFormat extends Model {

    protected $table = 'file_formats';

    public $timestamps = false;

    protected $fillable = ['code','name','file','description'];

    public function expense_types()
    {
        return $this->belongsToMany('IntranetMkt\Models\ExpenseTypes');
    }

}
