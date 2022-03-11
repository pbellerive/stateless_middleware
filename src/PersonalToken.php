<?php
namespace Laravue3\Stateless;

use Illuminate\Database\Eloquent\Model;

class PersonalToken extends Model
{
    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(config('stateless.userModel'));
    }
}
