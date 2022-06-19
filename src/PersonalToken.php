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

    public static function findToken($token)
    {
        if (strpos($token, '|') === false) {
            return static::where('token', hash('sha256', $token))->first();
        }

        [$id, $token] = explode('|', $token, 2);

        if ($instance = static::find($id)) {
            return hash_equals($instance->token, hash('sha256', $token)) ? $instance : null;
        }
    }
}
