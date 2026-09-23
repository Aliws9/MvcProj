<?php

namespace System\Database\Traits;

trait HasAttribute
{
    /**
     * دریافت مقدار یک attribute
     *
     * مثال:
     * $user->email
     */
    public function __get($attribute)
    {
        // ابتدا attributeهای دیتابیس
        if (array_key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }

        // اگر property واقعی در کلاس وجود داشته باشد
        if (property_exists($this, $attribute)) {
            return $this->$attribute;
        }

        return null;
    }

    /**
     * تعیین مقدار یک attribute
     *
     * مثال:
     * $user->email = 'test@example.com';
     */
    public function __set($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    /**
     * بررسی وجود یک attribute
     *
     * برای:
     * isset($this->email)
     */
    public function __isset($attribute)
    {
        return isset($this->attributes[$attribute]);
    }

    /**
     * ثبت یک attribute در Model
     */
    private function registerAttribute($object, string $attribute, $val)
    {
        if ($this->inCatsAttributes($attribute) == true) {
            $object->__set(
                $attribute,
                $this->castDecodeValue($attribute, $val)
            );
        } else {
            $object->__set($attribute, $val);
        }
    }

    /**
     * تبدیل آرایه دیتابیس به یک Model Object
     */
    protected function arrayToAttributes(array $array, $object = null)
    {
        if (!$object) {
            $className = get_called_class();
            $object = new $className;
        }

        foreach ($array as $attribute => $value) {
            if ($this->inHiddenAttributes($attribute) == true) {
                continue;
            }

            $this->registerAttribute($object, $attribute, $value);
        }

        return $object;
    }

    /**
     * تبدیل چند رکورد دیتابیس به collection
     */
    protected function arrayToObjects(array $array)
    {
        $collection = [];

        foreach ($array as $val) {
            $object = $this->arrayToAttributes($val);
            array_push($collection, $object);
        }

        $this->collection = $collection;
    }

    /**
     * بررسی hidden بودن attribute
     */
    private function inHiddenAttributes($attribute)
    {
        return in_array($attribute, $this->hidden);
    }

    /**
     * بررسی وجود attribute در casts
     */
    private function inCatsAttributes($attribute)
    {
        return in_array($attribute, array_keys($this->casts));
    }

    /**
     * تبدیل مقدار دیتابیس به نوع تعریف شده در casts
     */
    private function castDecodeValue($attributeKey, $val)
    {
        if (
            $this->casts[$attributeKey] == 'array' ||
            $this->casts[$attributeKey] == 'object'
        ) {
            return unserialize($val);
        }

        return $val;
    }

    /**
     * تبدیل مقدار attribute برای ذخیره در دیتابیس
     */
    private function castEncodeValue($attributeKey, $val)
    {
        if (
            $this->casts[$attributeKey] == 'array' ||
            $this->casts[$attributeKey] == 'object'
        ) {
            return serialize($val);
        }

        return $val;
    }

    /**
     * تبدیل تمام مقادیر قبل از ذخیره در دیتابیس
     */
    private function arrayToCastEncodeValue($vals)
    {
        $newArray = [];

        foreach ($vals as $attr => $value) {
            $this->inCatsAttributes($attr) == true
                ? $newArray[$attr] = $this->castEncodeValue($attr, $value)
                : $newArray[$attr] = $value;
        }

        return $newArray;
    }
}