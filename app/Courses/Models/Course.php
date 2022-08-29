<?php

namespace App\Courses\Models;

use App\Users\Models\User;
use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'course_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'author_id' => 'integer',
        'all_content' => 'string',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'user_id');
    }

    public function content()
    {
        return $this->hasMany(CourseItems::class, 'course_id', 'course_id')->withTrashed();
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'assignments', 'course_id', 'student_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'course_id', 'course_id');
    }

    protected static function newFactory() : CourseFactory
    {
        return CourseFactory::new();
    }

    public function scopeSearch($query, $searchParam)
    {
        return $query->where('title', 'like', '%'.$searchParam.'%')
            ->orwhere('description', 'like', '%'.$searchParam.'%');
    }
}
