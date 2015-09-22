<?php namespace IntranetMkt;

use Illuminate\Database\Eloquent\Model;

class SponsorshipApplication extends Model {

    protected $table = 'sponsorship_applications';

    public $timestamps = false;

    protected $fillable = ['file_format_id', 'expense_id', 'is_national', 'name',
        'specialty', 'city_of_residence', 'phone', 'email', 'is_government_employee',
        'hospital', 'role', 'is_seller', 'decision_is_in', 'member_of_social_medicine_id',
        'academic_responsibility', 'inscription', 'hotel', 'transport', 'food',
        'he_was_sponsored', 'name_last_event', 'location_last_event', 'question_1', 'question_2', 'question_3',
        'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'question_9', 'start', 'end',
        'hotel_name', 'hotel_address', 'hotel_location', 'hotel_description', 'description'];


    public function expense()
    {
        return $this->belongsTo('IntranetMkt\Models\Expense');
    }

    public function file_format()
    {
        return $this->belongsTo('IntranetMkt\Models\FileFormat');
    }

}
