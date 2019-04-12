<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    public static $rules = [
        "name"  => "required",
        "email" => "required",
    ];
    protected $fillable = [
        'name',
        'email',
    ];
    protected $dates = [];

    // Relationships

}
