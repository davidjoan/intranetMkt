<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class Cycle extends Model {

    protected $table = 'cycles';

    protected $fillable = ['code','name', 'month','year','start','end','description','active'];

}
