<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class RecaptchaValidationException extends AuthenticationException
{
    public function __construct(string $message = 'ReCAPTCHA validation failed. Please try again.')
    {
        parent::__construct($message);
    }
}
