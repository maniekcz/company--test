<?php

declare(strict_types=1);

namespace Lendinvest\Common;

abstract class Enum
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Enum constructor.
     * @param $value
     * @throws \ReflectionException
     */
    public function __construct($value)
    {
        if ($value instanceof static) {
            $this->value = $value->getValue();

            return;
        }

        if (!$this->isValid($value)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . \get_called_class());
        }

        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return (string)$this->value;
    }

    /**
     * @param Enum|null $enum
     * @return bool
     */
    final public function equals(Enum $enum = null)
    {
        return $enum !== null && $this->getValue() === $enum->getValue() && \get_called_class() === \get_class($enum);
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    public static function toArray()
    {
        $class = \get_called_class();
        if (!isset(static::$cache[$class])) {
            $reflection = new \ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * @param $value
     * @return bool
     * @throws \ReflectionException
     */
    public static function isValid($value)
    {
        return \in_array($value, static::toArray(), true);
    }

    /**
     * @param $name
     * @param $arguments
     * @return Enum
     * @throws \ReflectionException
     */
    public static function __callStatic($name, $arguments)
    {
        $array = static::toArray();
        if (isset($array[$name]) || \array_key_exists($name, $array)) {
            return new static($array[$name]);
        }

        throw new \BadMethodCallException("No static method or enum constant '$name' in class " . \get_called_class());
    }
}
