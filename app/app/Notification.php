<?php

namespace App;

use Parsedown;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'created_by', 'icon', 'body', 'action_text', 'action_url'];

    protected $casts = [
        'read' => 'boolean',
    ];

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
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
