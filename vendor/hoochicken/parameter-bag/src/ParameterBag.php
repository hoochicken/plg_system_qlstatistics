<?php

namespace Hoochicken\ParameterBag;

class ParameterBag
{
    private array $params = [];
    private static ?ParameterBag $parameterBag = null;

    public function __construct(array $params = [])
    {
        $this->setMultiple($params);
    }

    public function setMultiple($params)
    {
        if (!$params) return;
        foreach ($params as $k => $v) {
            $this->params[$k] = $v;
        }
    }

    public function get($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function getBool($key, ?bool $default = null): ? bool
    {
        $return = $this->params[$key] ?? $default;
        return (bool) $return;
    }

    public function getString($key, ?string $default = null): ? string
    {
        $return = $this->params[$key] ?? $default;
        return (string) $return;
    }

    public function getInt($key, ?int $default = null): ? int
    {
        $return = $this->params[$key] ?? $default;
        return (int) $return;
    }

    public function getArray($key, ?array$default = null): ? array
    {
        $return = $this->params[$key] ?? $default;
        return (array) $return;
    }

    public function getIntVal($key, ?int$default = null): ? int
    {
        $return = intval($this->params[$key] ?? $default);
        return (int)$return;
    }

    public function set($key, $value): void
    {
        $this->params[$key] = $value;
    }

    public function setBool($key, $value): void
    {
        $this->params[$key] = (bool) $value;
    }

    public function setString($key, $value): void
    {
        $this->params[$key] = (string) $value;
    }

    public function setInt($key, $value): void
    {
        $this->params[$key] = (int) $value;
    }

    public function setArray($key, $value): void
    {
        $this->params[$key] = (array) $value;
    }

    private static function getInstance($params = [])
    {
        if (null === self::$parameterBag) {
            self::$parameterBag = new ParameterBag($params);
        }
        return self::$parameterBag;
    }

    public static function getElement($params, $key, $default)
    {
        return self::getInstance($params)->get($key, $default);
    }

    public static function getElementBool($params, $key, $default)
    {
        return self::getInstance($params)->getBool($key, $default);
    }

    public static function getElementString($params, $key, $default)
    {
        return self::getInstance($params)->getString($key, $default);
    }

    public static function getElementInt($params, $key, $default)
    {
        return self::getInstance($params)->getInt($key, $default);
    }

    public static function getElementArray($params, $key, $default)
    {
        return self::getInstance($params)->getArray($key, $default);
    }

    public static function getElementIntVal($params, $key, $default)
    {
        return self::getInstance($params)->getIntVal($key, $default);
    }
}
