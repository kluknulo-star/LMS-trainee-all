<?php

namespace App\Courses\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assignments';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'assignment_id';

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
        'student_id' => 'integer',
        'course_id' => 'integer',
        'progress' => 'integer',
    ];

    public function stats()
    {
        return $this->hasMany(ItemsStats::class, 'user_id', 'student_id');
    }
}
