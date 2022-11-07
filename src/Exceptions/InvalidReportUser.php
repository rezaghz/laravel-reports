<?php

namespace Rezaghz\Laravel\Report\Exceptions;

class InvalidReportUser extends \Exception
{
    /**
     * Report user not defined.
     *
     * @return static
     */
    public static function notDefined()
    {
        return new static('Report user not defined.');
    }

    /**
     * Invalid reaction user.
     *
     * @return static
     */
    public static function invalidReportByUser()
    {
        return new static('Invalid Report user.');
    }
}
