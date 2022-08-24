<?php

namespace App\Courses\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsStats extends Model
{
    use HasFactory;
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
}
