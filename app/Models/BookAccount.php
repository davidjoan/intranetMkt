<?php namespace IntranetMkt\Models;

use Illuminate\Database\Eloquent\Model;

class BookAccount extends Model {

    protected $table = 'book_accounts';

    protected $fillable = ['code','name','description','active'];

}
