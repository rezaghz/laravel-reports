<?php

namespace Rezaghz\Laravel\Reports\Contracts;

interface ReportsInterface
{
    /**
     * Reaction on reactable model.
     *
     * @param ReportableInterface $reportable
     * @param mixed $type
     * @return void
     */
    public function reportTo(ReportableInterface $reportable, $type);
}
