<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Common;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class EnumTest extends TestCase
{
    /**
     * @test
     */
    public function testStaticAccess()
    {
        $this->assertEquals(new EnumFixture(EnumFixture::FOO), EnumFixture::FOO());
        $this->assertEquals(new EnumFixture(EnumFixture::BAR), EnumFixture::BAR());
        $this->assertEquals(new EnumFixture(EnumFixture::NUMBER), EnumFixture::NUMBER());
    }

    /**
     * getValue()
     */
    public function testGetValue()
    {
        $value = new EnumFixture(EnumFixture::FOO);
        $this->assertEquals(EnumFixture::FOO, $value->getValue());
        $value = new EnumFixture(EnumFixture::BAR);
        $this->assertEquals(EnumFixture::BAR, $value->getValue());
        $value = new EnumFixture(EnumFixture::NUMBER);
        $this->assertEquals(EnumFixture::NUMBER, $value->getValue());
    }

    /**
     * @dataProvider invalidValueProvider
     */
    public function testCreatingEnumWithInvalidValue($value)
    {
        $this->expectException(UnexpectedValueException::class);
        new EnumFixture($value);
    }
    /**
     * Contains values not existing in EnumFixture
     * @return array
     */
    public function invalidValueProvider()
    {
        return array(
            "string" => array('test'),
            "int" => array(1234),
        );
    }

    /**
     * __toString()
     * @dataProvider toStringProvider
     */
    public function testToString($expected, $enumObject)
    {
        $this->assertSame($expected, (string) $enumObject);
    }
    public function toStringProvider()
    {
        return array(
            array(EnumFixture::FOO, new EnumFixture(EnumFixture::FOO)),
            array(EnumFixture::BAR, new EnumFixture(EnumFixture::BAR)),
            array((string) EnumFixture::NUMBER, new EnumFixture(EnumFixture::NUMBER)),
        );
    }

    /**
     * @test
     */
    public function testBadStaticAccess()
    {
        $this->expectException(BadMethodCallException::class);
        EnumFixture::UNKNOWN();
    }

    /**
     * @test
     */
    public function testConstructWithSameEnumArgument()
    {
        $enum = new EnumFixture(EnumFixture::FOO);
        $enveloped = new EnumFixture($enum);
        $this->assertEquals($enum, $enveloped);
    }
}