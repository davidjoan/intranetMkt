<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCampaign extends Model {

    protected $table = 'medical_campaigns';

    protected $fillable = ['file_format_id', 'expense_id', 'consultor','medical_campaign_type', 'delivery_date',
        'place', 'cmp', 'doctor', 'estimated_value', 'description', 'status'];


    public function expense()
    {
        return $this->belongsTo('IntranetMkt\Models\Expense');
    }

    public function file_format()
    {
        return $this->belongsTo('IntranetMkt\Models\FileFormat');
    }




}
