<?php

use Tests\{
    CreatesApplication, TestsHelper
};

abstract class TestCase extends \Laravel\BrowserKitTesting\TestCase
{
    use CreatesApplication, TestsHelper;
}
