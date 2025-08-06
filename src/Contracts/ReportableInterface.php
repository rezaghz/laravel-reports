<?php

namespace Rezaghz\Laravel\Reports\Contracts;

interface ReportableInterface
{
    /**
     * Collection of reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reports();

    /**
     * Add report.
     *
     * @param mixed $reportType
     * @param mixed $user
     * @return \Rezaghz\Laravel\Reports\Models\Report|bool
     */
    public function report($reportType, $user = null);

    /**
     * Remove report.
     *
     * @param mixed $user
     * @return bool
     */
    public function removeReport($user = null);

    /**
     * Toggle Report.
     *
     * @param mixed $reportType
     * @param mixed $user
     * @return \Rezaghz\Laravel\Reports\Models\Report|void
     */
    public function toggleReport($reportType, $user = null);

    /**
     * Report on reportable model by user.
     *
     * @param mixed $user
     * @return \Rezaghz\Laravel\Reports\Models\Report|null
     */
    public function reported($user = null);

    /**
     * Check is reported by user.
     *
     * @param mixed $user
     * @param mixed $type
     * @return bool
     */
    public function isReportBy($user = null, $type = null);
}
