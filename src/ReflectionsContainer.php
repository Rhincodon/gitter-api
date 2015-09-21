<?php

namespace Rhinodontypicus\GitterApi;

use ReflectionClass;
use ReflectionProperty;

class ReflectionsContainer
{
    /**
     * @var array
     */
    static $reflectionsIndexes = [];

    /**
     * @var ReflectionClass[]
     */
    static $reflections = [];

    /**
     * @param $className
     * @return array
     */
    public static function getExistsProperties($className)
    {
        if ($index = static::inContainer($className)) {
            return static::getProperties(static::$reflections[$index]->getProperties());
        }

        $reflectionClass = self::addReflectionToContainer($className);

        return static::getProperties($reflectionClass->getProperties());
    }

    /**
     * @param $properties ReflectionProperty[]
     * @return array
     */
    public static function getProperties($properties)
    {
        $result = array();
        foreach ($properties as $property) {
            array_push($result, $property->name);
        }

        return $result;
    }

    /**
     * @param $className string
     * @return integer|boolean
     */
    public static function inContainer($className)
    {
        return array_search($className, static::$reflectionsIndexes);
    }

    /**
     * @param $className
     * @return ReflectionClass
     */
    public static function addReflectionToContainer($className)
    {
        $reflectionClass = new ReflectionClass($className);
        array_push(static::$reflectionsIndexes, $className);
        array_push(static::$reflections, $reflectionClass);

        return $reflectionClass;
    }
}
