<?php

namespace Rezaghz\Laravel\Report\Contracts;

interface ReportableInterface
{
    /**
     * Collection of reactions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reports();
}
