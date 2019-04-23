<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are required.
     *
     * @var array
     */
    public static $rules = [
        'name'  => 'required',
        'email' => 'required',
        'password' => 'required',
        'address' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'active', // default: false
        'authentication_method', // default: 'email'
        'email',
        'password', // nullable
        'address'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'authentication_method',
        'password'
    ];

    /**
     * The fields that will be transformed by Carbon.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
