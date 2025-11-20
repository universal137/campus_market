<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication; // 提供 createApplication()，否则 PHPUnit 无法运行
}
