<?php

namespace Rezaghz\Laravel\Report\Events;

use Rezaghz\Laravel\Report\Contracts\ReportableInterface;
use Rezaghz\Laravel\Report\Contracts\ReportsInterface;
use Rezaghz\Laravel\Report\Models\Report;

class OnReport
{
    /**
     * The reportable model.
     *
     * @var ReportableInterface
     */
    public $reportable;

    /**
     * User who reported on model.
     *
     * @var ReportsInterface
     */
    public $reportBy;

    /**
     * Report model.
     *
     * @var Report
     */
    public $report;

    /**
     * Create a new event instance.
     *
     * @param  ReportableInterface  $reportable
     * @param  Report  $report
     * @param  ReportsInterface  $reportBy
     * @return void
     */
    public function __construct(ReportableInterface $reportable, Report $report, ReportsInterface $reportBy)
    {
        $this->reportable = $reportable;
        $this->report = $report;
        $this->reportBy = $reportBy;
    }
}
