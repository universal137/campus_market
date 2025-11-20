<?php

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\CreatesApplication; // 让测试能够启动完整的 Laravel 应用
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication; // 提供 createApplication()，否则 PHPUnit 无法运行
}
