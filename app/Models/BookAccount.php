<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class BookAccount extends Model {

    protected $table = 'book_accounts';

    public $timestamps = false;

    protected $fillable = ['code','name','description','active'];

}
