<?php namespace yii\helpers;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use yii\base\Arrayable;
use yii\helpers\BaseArrayHelper as BaseArrayHelper;

/**
 * This class is based on this github post https://github.com/yiisoft/yii2/issues/6844#issuecomment-131482508
 */
class ArrayHelper extends BaseArrayHelper
{
    /**
     * @inheritdoc
     */
    public static function toArray($object, $properties = [], $recursive = true, $expands = [])
    {
        if (is_array($object)) {
            if ($recursive) {
                foreach ($object as $key => $value) {
                    if (is_array($value) || is_object($value)) {
                        if (is_int($key)) {
                            $expand = $expands;
                        } elseif (isset ($expands[$key])) {
                            $expand = $expands[$key];
                        } else {
                            $expand = [];
                        }
                        $object[$key] = static::toArray($value, $properties, true, $expand);
                    }
                }
            }

            return $object;
        } elseif (is_object($object)) {
            if (!empty($properties)) {
                $className = get_class($object);
                if (!empty($properties[$className])) {
                    $result = [];
                    foreach ($properties[$className] as $key => $name) {
                        if (is_int($key)) {
                            $result[$name] = $object->$name;
                        } else {
                            $result[$key] = static::getValue($object, $name);
                        }
                    }

                    return $recursive ? static::toArray($result, $properties) : $result;
                }
            }
            if ($object instanceof Arrayable) {
                $result = $object->toArray([], $expands, $recursive);
            } else {
                $result = [];
                foreach ($object as $key => $value) {
                    $result[$key] = $value;
                }
            }

            return $recursive ? static::toArray($result, [], true, $expands) : $result;
        } else {
            return [$object];
        }
    }

    /**
     * @param string $value
     * @param bool   $trim
     *
     * @return array|string[]
     */
    public static function commaSeparated($value, $trim = false)
    {
        if (is_array($value)) {
            return $value;
        }

        $value = explode(',', $value);

        if ($trim) {
            return array_map(function ($_value) {
                return trim($_value);
            }, $value);
        }

        return $value;
    }
}
