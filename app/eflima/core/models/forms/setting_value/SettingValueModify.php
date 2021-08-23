<?php namespace eflima\core\models\forms\setting_value;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use Closure;
use eflima\core\components\Setting;
use eflima\core\components\SettingOptions;
use eflima\core\models\SettingValue;
use yii\base\InvalidConfigException;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property-read SettingOptions|null $options
 */
class SettingValueModify extends SettingValue
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        $options = $this->getOptions();
        $rules = ['safe'];

        if ($options) {
            if ($options->rules instanceof Closure) {
                $rules = call_user_func($options->rules, $this->setting);
            } else {
                $rules = $options->rules;
            }
        }


        foreach ($rules as $key => $rule) {
            $rules[$key] = (array) $rules[$key];

            array_unshift($rules[$key], 'value');
        }


        return $rules;
    }

    /**
     * @return SettingOptions|null
     * @throws InvalidConfigException
     */
    public function getOptions()
    {
        return Setting::getOptions($this->setting_code);
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        $options = $this->getOptions();

        return [
            'value' => $options && !empty($options->label) ? $options->label : $this->setting_code,
        ];
    }
}
