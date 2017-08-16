<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
//use Laravel\BrowserKitTesting\TestCase;

class FeatureTestCase extends TestCase
{
    use DatabaseTransactions;

    public function seeErrors(array $fields)
    {
        foreach ($fields as $name => $errors) {
            foreach ((array) $errors as $message) {
                $this->seeInElement(
                    "#field_{$name}.has-error .help-block", $message
                );
            }
        }
    }
}
