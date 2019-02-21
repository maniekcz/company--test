<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Common;

use Lendinvest\Common\Enum;

/**
 * Class EnumFixture
 * @method static EnumFixture FOO()
 * @method static EnumFixture BAR()
 * @method static EnumFixture NUMBER()
 */
class EnumFixture extends Enum
{
    const FOO = "foo";
    const BAR = "bar";
    const NUMBER = 42;
}