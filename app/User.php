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
        'address' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'active',
        'email',
        'address'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'active'
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
