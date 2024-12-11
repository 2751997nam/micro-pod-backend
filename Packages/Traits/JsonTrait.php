<?php

namespace App\Packages\Traits;

use ReflectionClass;
use App\Packages\Utils\Utils;

trait JsonTrait
{
    public function mapJson($input)
    {
        $reflect = new ReflectionClass($this);

        foreach ($input as $key => $value) {
            $property = preg_replace("/_(\w)/", "\\\U$1", $key);
            if (property_exists($this, $property)) {
                if (is_array($value)) {
                    if (gettype(array_key_first($value)) == 'string') {
                        $prop = $reflect->getProperty($property);
                        $className = $prop->getType()->getName();
                        $obj = new $className();
                        $obj->mapJson($value);
                        $this->$property = $obj;
                    } else {
                        $arr = [];
                        foreach ($value as $k => $v) {
                            $arr[$k] = $this->mapJson($v);
                        }

                        $this->$property = $arr;
                    }
                    continue;
                }
                $this->$property = $value;
            }
        }
    }

    public function toJsonArray()
    {
        return json_decode(json_encode($this), true);
    }

    public function jsonSerialize()
    {
        $retVal = [];
        $reflect = new ReflectionClass($this);
        $properties = $reflect->getProperties();

        foreach ($properties as $property) {
            if (!$property->isInitialized($this)) {
                continue;
            }
            $name = $property->getName();
            if (gettype($property->getValue($this)) == 'object') {
                $retVal[$name] = $property->getValue($this)->jsonSerialize();
            } else if (method_exists($this, 'get' . ucfirst($name))) {
                $retVal[Utils::toSnakeCase($name)] = $this->{'get' . ucfirst($name)}();
            } else {
                $property->setAccessible(true);  // Make private/protected properties accessible
                $retVal[Utils::toSnakeCase($name)] = $property->getValue($this);
            }
        }

        return $retVal;
    }
}