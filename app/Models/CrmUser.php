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
    const NEXTCLOUD_STORAGE = 'data';
    const USER_FOLDER = 'admin';

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
        return $this->hasOne(CrmToken::class, 'crm_user_id');
    }

    /**
     * @return HasMany
     */

    public function crmTokens(): HasMany
    {
        return $this->hasMany(CrmToken::class);
    }
}
