<?php

namespace App\Courses\Models;

use App\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemsStats extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'course_items_users_stats';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'stat_id';

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
        'user_id' => 'integer',
        'item_id' => 'integer',
        'status' => 'string',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
