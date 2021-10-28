<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CrmToken extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'crm_user_id',
        'token',
        'last_used_at'
    ];

    public function crmUser (): HasOne
    {
        return $this->hasOne(CrmUser::class, 'crm_user_id');
    }

}
