<?php

namespace Rezaghz\Laravel\Reports\Contracts;

interface ReportsInterface
{
    /**
     * Report on reportable model.
     *
     * @param ReportableInterface $reportable
     * @param mixed $type
     * @return \Rezaghz\Laravel\Reports\Models\Report
     */
    public function reportTo(ReportableInterface $reportable, $type);

    /**
     * Remove report from reportable model.
     *
     * @param ReportableInterface $reportable
     * @return void
     */
    public function removeReportFrom(ReportableInterface $reportable);

    /**
     * Toggle report on reportable model.
     *
     * @param ReportableInterface $reportable
     * @param mixed $type
     * @return \Rezaghz\Laravel\Reports\Models\Report|void
     */
    public function toggleReportOn(ReportableInterface $reportable, $type);

    /**
     * Check is reported on reportable model.
     *
     * @param ReportableInterface $reportable
     * @param mixed $type
     * @return bool
     */
    public function isReportedOn(ReportableInterface $reportable, $type = null);
}
