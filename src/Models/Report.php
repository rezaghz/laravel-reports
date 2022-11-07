<?php

namespace Rezaghz\Laravel\Report\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Qirolab\Laravel\Reactions\Traits\Reactable;
use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;

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
     * Get the user that reacted on Reportable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reportBy()
    {
        $userModel = config('auth.providers.users.model');

        return $this->belongsTo($userModel, 'user_id');
    }
}
