<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class RequestAttention extends Model {

    protected $table = 'request_attentions';

    public $timestamps = false;

    protected $fillable = ['file_format_id',
        'expense_id',
        'promotora', 'description', 'delivery_date', 'client_code', 'client',
        'price_unit', 'quantity', 'estimated_value', 'reason', 'status'];

    public function expense()
    {
        return $this->belongsTo('IntranetMkt\Models\Expense');
    }

    public function file_format()
    {
        return $this->belongsTo('IntranetMkt\Models\FileFormat');
    }
}
