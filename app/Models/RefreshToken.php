<?php

namespace App\Models;

use App\Traits\UUIDS;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefreshToken extends Model
{
    /**
     * Keep the session info but revoke access
     */
    use SoftDeletes;

    /**
     * Use uuids
     */
    use UUIDS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'refresh_token',
        'expires_at'
    ];

    /**
     * The fields that will be transformed by Carbon.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expires_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'refresh_token',
        'user_id',
        'deleted_at'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;


    /**
     * Get the user of the refresh_token.
     */
    function user() {
        return $this->belongsTo(User::class);
    }
}
