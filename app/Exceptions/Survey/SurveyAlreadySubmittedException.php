<?php

namespace App\Exceptions\Survey;

/**
 * Thrown if a user tries to submit a survey twice
 *
 * Class SurveyAlreadySubmittedException
 */
class SurveyAlreadySubmittedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Survey already submitted");
    }

}
