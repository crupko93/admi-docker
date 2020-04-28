<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\{OwnershipMethods,TablePaginate};
use Illuminate\Database\Eloquent\Model;
use Parsedown;

class Announcement extends Model
{
    use OwnershipMethods, TablePaginate;

    protected $fillable = ['user_id', 'body', 'action_text', 'action_url'];

    protected $searchable = ['body', 'creator_id'];

    /*
     |--------------------------------------------------------------------------
     | Accessors
     |--------------------------------------------------------------------------
     |
     */

    public function getParsedBodyAttribute()
    {
        return (new Parsedown)->text(htmlspecialchars($this->attributes['body']));
    }

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_id');
    }
}
