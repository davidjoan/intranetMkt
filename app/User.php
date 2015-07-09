<?php namespace IntranetMkt;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	protected $table = 'users';

    protected $fillable = ['role_id','cost_center_id', 'supervisor_id',
        'code', 'name', 'email','phone', 'username','password', 'photo'];

	protected $hidden = ['password', 'remember_token'];

    public function role()
    {
        return $this->belongsTo('IntranetMkt\Models\Role');
    }

    public function divisions()
    {
        return $this->belongsToMany('IntranetMkt\Models\Division');
    }

}
