<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'email'
    ];

    /**
     * @return HasOne
     */

    public function crmToken(): HasOne
    {
        return $this->hasOne(CrmToken::class);
    }

    /**
     * @return HasMany
     */

    public function crmTokens(): HasMany
    {
        return $this->hasMany(CrmToken::class);
    }
}
