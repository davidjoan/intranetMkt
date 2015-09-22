<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class Entertainment extends Model {

    protected $table = 'entertainments';

    public $timestamps = false;

    protected $fillable = ['file_format_id', 'expense_id', 'consultor','entertainment_type', 'delivery_date',
        'place', 'qty_doctors', 'estimated_value', 'description', 'status'];

    public function expense()
    {
        return $this->belongsTo('IntranetMkt\Models\Expense');
    }

    public function file_format()
    {
        return $this->belongsTo('IntranetMkt\Models\FileFormat');
    }




}
