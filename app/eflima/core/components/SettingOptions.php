<?php namespace eflima\core\components;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\core\models\SettingValue;
use yii\base\BaseObject;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class SettingOptions extends BaseObject
{
    /** @var string */
    public $label;

    /** @var array */
    public $rules = [];

    /**
     * @param SettingValue $model
     * @param Setting      $setting
     *
     * @return string
     */
    public function typeCast($model, $setting)
    {
        return $model->value;
    }
}

