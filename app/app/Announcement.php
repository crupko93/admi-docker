<?php

namespace App;

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

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
