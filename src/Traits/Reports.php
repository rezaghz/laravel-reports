<?php

namespace Rezaghz\Laravel\Reports\Traits;

use Rezaghz\Laravel\Reports\Contracts\ReportableInterface;
use Rezaghz\Laravel\Reports\Events\OnDeleteReport;
use Rezaghz\Laravel\Reports\Events\OnReport;
use Rezaghz\Laravel\Reports\Models\Report;

trait Reports
{
    /**
     * Report on reportable model.
     *
     * @param ReportableInterface $reportable
     * @param mixed $type
     * @return Report
     */
    public function reportTo(ReportableInterface $reportable, $type)
    {
        $report = $reportable->reports()->where([
            'user_id' => $this->getKey(),
        ])->first();

        if (!$report) {
            return $this->storeReport($reportable, $type);
        }

        if ($report->type == $type) {
            return $report;
        }

        $this->deleteReport($report, $reportable);

        return $this->storeReport($reportable, $type);
    }

    /**
     * Remove report from reportable model.
     *
     * @param ReportableInterface $reportable
     * @return void
     */
    public function removeReportFrom(ReportableInterface $reportable)
    {
        $report = $reportable->reports()->where([
            'user_id' => $this->getKey(),
        ])->first();

        if (!$report) {
            return;
        }

        $this->deleteReport($report, $reportable);
    }

    /**
     * Toggle report on reportable model.
     *
     * @param ReportableInterface $reportable
     * @param mixed $type
     * @return void
     */
    public function toggleReportOn(ReportableInterface $reportable, $type)
    {
        $report = $reportable->reports()->where([
            'user_id' => $this->getKey(),
        ])->first();

        if (!$report) {
            return $this->storeReport($reportable, $type);
        }

        $this->deleteReport($report, $reportable);

        if ($report->type == $type) {
            return;
        }

        return $this->storeReport($reportable, $type);
    }

    /**
     * Report on reportable model.
     *
     * @param ReportableInterface $reportable
     * @return Report
     */
    public function ReportedOn(ReportableInterface $reportable)
    {
        return $reportable->reported($this);
    }

    /**
     * Check is reported on reportable model.
     *
     * @param ReportableInterface $reportable
     * @param mixed $type
     * @return bool
     */
    public function isReportedOn(ReportableInterface $reportable, $type = null)
    {
        $isReported = $reportable->reports()->where([
            'user_id' => $this->getKey(),
        ]);

        if ($type) {
            $isReported->where([
                'type' => $type,
            ]);
        }

        return $isReported->exists();
    }

    /**
     * Store report.
     *
     * @param ReportableInterface $reportable
     * @param mixed $type
     * @return Report
     */
    protected function storeReport(ReportableInterface $reportable, $type)
    {
        $report = $reportable->reports()->create([
            'user_id' => $this->getKey(),
            'type' => $type,
        ]);

        event(new OnReport($reportable, $report, $this));

        return $report;
    }

    /**
     * Delete report.
     *
     * @param Report $report
     * @param ReportableInterface $reportable
     * @return void
     */
    protected function deleteReport(Report $report, ReportableInterface $reportable)
    {
        $response = $report->delete();

        event(new OnDeleteReport($reportable, $report, $this));

        return $response;
    }
}
