<?php

namespace Rezaghz\Laravel\Reports\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reports';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
    ];

    /**
     * Reportable model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reportable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that reported on Reportable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reportBy()
    {
        $userModel = config('auth.providers.users.model');

        return $this->belongsTo($userModel, 'user_id');
    }
}
