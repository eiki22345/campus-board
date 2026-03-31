<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Validation\Rules\Password;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Password::defaults(function () {
            return Password::min(8)->letters()->mixedCase()->numbers();
        });
    }
}
