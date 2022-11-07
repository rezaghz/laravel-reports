<?php

namespace Rezaghz\Laravel\Report\Traits;

use Rezaghz\Laravel\Report\Models\Report;
use Illuminate\Database\Eloquent\Builder;
use Rezaghz\Laravel\Report\Exceptions\InvalidReportUser;
use Rezaghz\Laravel\Report\Contracts\ReportsInterface;

trait Reportable
{
    /**
     * Collection of reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Get collection of users who reported on reportable model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function reportsBy()
    {
        $userModel = $this->resolveUserModelObj();

        $userIds = $this->reports->pluck('user_id');

        return $userModel::whereKey($userIds)->get();
    }

    /**
     * Attribute to get collection of users who reported on reportable model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getReportsByAttribute()
    {
        return $this->reportsBy();
    }

    /**
     * Report summary.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function reportSummary()
    {
        return $this->reports->groupBy('type')->map(function ($val) {
            return $val->count();
        });
    }

    /**
     * Report summary attribute.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getReportSummaryAttribute()
    {
        return $this->reportSummary();
    }

    /**
     * Add report.
     *
     * @param mixed $reportType
     * @param mixed $user
     * @return Reports|bool
     */
    public function report($reportType, $user = null)
    {
        $user = $this->getUserObj($user);

        if ($user) {
            return $user->reportTo($this, $reportType);
        }

        return false;
    }

    /**
     * Remove report.
     *
     * @param mixed $user
     * @return bool
     */
    public function removeReport($user = null)
    {
        $user = $this->getUserObj($user);

        if ($user) {
            return $user->removeReportFrom($this);
        }

        return false;
    }

    /**
     * Toggle Report.
     *
     * @param mixed $reportType
     * @param mixed $user
     * @return void|Reports
     */
    public function toggleReport($reportType, $user = null)
    {
        $user = $this->getUserObj($user);

        if ($user) {
            return $user->toggleReportOn($this, $reportType);
        }
    }

    /**
     * Report on reportable model by user.
     *
     * @param mixed $user
     * @return Reports
     */
    public function reported($user = null)
    {
        $user = $this->getUserObj($user);

        return $this->reports->where('user_id', $user->getKey())->first();
    }

    /**
     * Report on reportable model by user.
     *
     * @return Reports
     */
    public function getReportedAttribute()
    {
        return $this->reported();
    }

    /**
     * Check is reported by user.
     *
     * @param mixed $user
     * @return bool
     */
    public function isReportBy($user = null, $type = null)
    {
        $user = $this->getUserObj($user);

        if ($user) {
            return $user->isReportedOn($this, $type);
        }

        return false;
    }

    /**
     * Check is reported by user.
     *
     * @param mixed $user
     * @return bool
     */
    public function getIsReportedAttribute()
    {
        return $this->isReportBy();
    }

    /**
     * Fetch records that are reported by a given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @param null|int|ReportsInterface $userId
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throw InvalidReportUser
     * @todo think about method name
     *
     */
    public function scopeWhereReportedBy(Builder $query, $userId = null, $type = null)
    {
        $user = null;

        try {
            $user = $this->getUserObj($userId);
        } catch (InvalidReportUser $e) {
            if (!$user && !$userId) {
                throw InvalidReportUser::notDefined();
            }
        }

        $userId = ($user) ? $user->getKey() : $userId;

        return $query->whereHas('reports', function ($innerQuery) use ($userId, $type) {
            $innerQuery->where('user_id', $userId);

            if ($type) {
                $innerQuery->where('type', $type);
            }
        });
    }

    /**
     * Get user model.
     *
     * @param mixed $user
     * @return ReportsInterface
     *
     * @throw \Qirolab\Laravel\Reports\Exceptions\InvalidReportUser
     */
    private function getUserObj($user = null)
    {
        if (!$user && auth()->check()) {
            return auth()->user();
        }

        if ($user instanceof ReportsInterface) {
            return $user;
        }

        if (!$user) {
            throw InvalidReportUser::notDefined();
        }

        throw InvalidReportUser::invalidReportByUser();
    }

    /**
     * Retrieve User's model class name.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    private function resolveUserModelObj()
    {
        return config('auth.providers.users.model');
    }
}
