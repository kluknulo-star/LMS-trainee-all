<?php

namespace App\Users\Models;

use App\Courses\Models\Assignment;
use App\Courses\Models\Course;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property $surname
 * @property $name
 * @property $patronymic
 * @property $email
 * @property $password
 * @property $is_teacher
 * @property $avatar_filename
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'is_teacher'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'surname' => 'string',
        'name' => 'string',
        'patronymic' => 'string',
        'email' => 'string',
        'password' => 'string',
        'is_teacher' => 'boolean',
        'email_verified_at' => 'datetime',
        'avatar_filename' => 'string',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'author_id', 'user_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'student_id', 'user_id');
    }

    public function assignedCourses()
    {
        return $this->belongsToMany(Course::class, 'assignments', 'student_id', 'course_id');
    }

    public function isEmailConfirmed()
    {
        return !is_null($this->email_confirmed_at);
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function scopeSearch($query, $searchParam)
    {
        return $query->where('surname', 'like', '%'.$searchParam.'%')
            ->orwhere('name', 'like', '%'.$searchParam.'%')
            ->orwhere('patronymic', 'like', '%'.$searchParam.'%');
    }

    public function getAvatarsPath(int $userId)
    {
        $path = "images/avatars/{$userId}";
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return "/$path/";
    }

    public function clearAvatars(int $userId)
    {
        $path = "images/avatars/{$userId}";

        if(file_exists(public_path("/$path"))) {
            foreach ( glob( public_path("$path/*") ) as $avatar ) {
                unlink($avatar);
            }
        }
    }
}
