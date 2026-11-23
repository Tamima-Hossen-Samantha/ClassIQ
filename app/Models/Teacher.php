<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Course;


class Teacher extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;

    /**
     * Guard name used by auth.
     */
    protected $guard = 'teacher';

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'school_name',
        'subjects_taught',
        'classes_taught',
        'nid_file_path',
        'password',
        'is_approved',
        'is_suspended',           // new: for admin suspend/activate
        'documents_verified_at',  // new: when admin verifies documents
        'last_login_at',          // new: track last login time
    ];

    /**
     * Hidden attributes for arrays / JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'email_verified_at'     => 'datetime',
        'is_approved'           => 'boolean',
        'is_suspended'          => 'boolean',
        'documents_verified_at' => 'datetime',
        'last_login_at'         => 'datetime',
        'deleted_at'            => 'datetime',
    ];

public function courses()
{
    return $this->hasMany(Course::class);
}


}
