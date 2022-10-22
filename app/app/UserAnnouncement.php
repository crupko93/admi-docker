<?php

namespace App;

use App\Models\ApprovalProcess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnnouncement extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'announcement_id',
        'comments',
        'approved',
    ];
}
