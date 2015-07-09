<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model {

    protected $table = 'divisions';

    protected $fillable = ['code','name', 'description'];

    public function users()
    {
        return $this->belongsToMany('IntranetMkt\User');
    }

}
