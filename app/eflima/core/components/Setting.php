<?php namespace eflima\core\components;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\Account;
use eflima\core\models\forms\setting_value\SettingValueModify;
use eflima\core\models\SettingValue;
use Exception;
use Throwable;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class Setting extends Component
{
    /** @var Account */
    protected $_account;

    /** @var array */
    protected $_cache = [];

    /** @var array */
    protected static $_options = [];

    protected static $_typeCasters = [
        [Setting::class, 'typeCastModel'],
    ];

    /**
     * @inheritDoc
     *
     * @param Account $account
     */
    public function __construct($config = [])
    {
        $this->_account = isset($config['account']) ? $config['account'] : null;

        unset($config['account']);

        parent::__construct($config);
    }

    /**
     * @param string|array $code
     * @param array        $options
     */
    public static function setOptions($code, $options = null)
    {
        if (is_array($code)) {
            foreach ($code as $_code => $value) {
                self::setOptions($_code, $value);
            }
        } else {
            self::$_options[$code] = $options;
        }
    }

    /**
     * @param string $code
     *
     * @return SettingOptions|null
     * @throws InvalidConfigException
     */
    public static function getOptions($code)
    {
        if (!isset(self::$_options[$code])) {
            return null;
        }

        if (!self::$_options[$code] instanceof SettingOptions) {
            $params = self::$_options[$code];
            $params['class'] = SettingOptions::class;

            self::$_options[$code] = Yii::createObject($params);
        }

        return self::$_options[$code];
    }

    /**
     * @param            $code
     *
     * @param mixed      $default
     *
     * @return array|SettingValue|void|null
     * @throws InvalidConfigException
     */
    public function getValue($code, $default = null)
    {
        if (isset($this->_cache[$code])) {
            return $this->_cache[$code];
        }

        $valueModel = null;

        if ($this->_account) {
            $valueModel = SettingValue::find()->joinWith('setting')
                ->andWhere([
                    'setting_value.setting_code' => $code,
                    'setting_value.account_id' => $this->_account->id,
                    'setting.is_overridable' => true,
                ])
                ->one();
        }

        if (!$valueModel) {
            $valueModel = SettingValue::find()->joinWith('setting')
                ->andWhere([
                    'setting_value.setting_code' => $code,
                    'setting_value.account_id' => null,
                ])
                ->one();
        }

        $value = $default;

        if ($valueModel) {
            $value = $this->typeCast($valueModel);
        }


        $this->_cache[$code] = $value;

        return $value;
    }

    /**
     * @param string[] $codes
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function getValues($codes)
    {
        $result = [];

        foreach ($codes as $codeIndex => $code) {
            if (isset($this->_cache[$code])) {
                $result[$code] = $this->_cache[$code];

                unset($codes[$codeIndex]);
            }
        }

        if (count($result) === count($codes)) {
            return $result;
        }

        if ($this->_account) {
            $valueModels = SettingValue::find()->joinWith('setting')
                ->andWhere([
                    'setting_value.setting_code' => $codes,
                    'setting_value.account_id' => $this->_account->id,
                    'setting.is_overridable' => true,
                ])
                ->indexBy('setting_code')
                ->all();


            if (count($valueModels) < count($codes)) {
                $unoverrideCodes = array_filter($codes, function ($code) use ($valueModels) {
                    return !isset($valueModels[$code]);
                });

                $defaultValueModels = SettingValue::find()->joinWith('setting')
                    ->andWhere([
                        'setting_value.setting_code' => $unoverrideCodes,
                        'setting_value.account_id' => null,
                    ])
                    ->indexBy('setting_code')
                    ->all();

                $valueModels = ArrayHelper::merge($valueModels, $defaultValueModels);
            }
        } else {
            $valueModels = SettingValue::find()->joinWith('setting')
                ->andWhere([
                    'setting_value.setting_code' => $codes,
                    'setting_value.account_id' => null,
                ])
                ->indexBy('setting_code')
                ->all();
        }

        foreach ($codes as $code) {
            if (isset($valueModels[$code])) {
                $value = $this->typeCast($valueModels[$code]);

                $this->_cache[$code] = $value;
                $result[$code] = $value;
            } else {
                $result[$code] = null;
            }
        }

        return $result;
    }

    /**
     * @param string|SettingValueModify $code
     * @param string                    $value
     *
     * @return bool|SettingValueModify
     */
    public function setValue($code, $value)
    {
        $model = $code instanceof SettingValueModify ? $code : $this->getModel($code);

        $model->value = $value;

        return $model->save();
    }

    /**
     * @param string $code
     *
     * @return SettingValueModify
     */
    public function getModel($code)
    {
        if ($this->_account) {
            $model = SettingValueModify::find()->joinWith('setting')
                ->andWhere([
                    'setting_value.setting_code' => $code,
                    'setting_value.account_id' => $this->_account->id,
                    'setting.is_overridable' => true,
                ])
                ->one();
        } else {
            $model = SettingValueModify::find()->joinWith('setting')
                ->andWhere([
                    'setting_value.setting_code' => $code,
                    'setting_value.account_id' => null,
                ])
                ->one();
        }

        if (!$model) {
            $model = new SettingValueModify([
                'setting_code' => $code,
                'account_id' => $this->_account ? $this->_account->id : null,
            ]);
        }

        return $model;
    }

    /**
     * @param string[] $codes
     *
     * @return SettingValueModify[]
     */
    public function getModels($codes)
    {
        $models = [];

        foreach ($codes as $code) {
            $models[$code] = $this->getModel($code);
        }

        return $models;
    }

    /**
     * @param array|SettingValueModify[] $values
     *
     * @return bool
     *
     * @throws Throwable
     */
    public function setValues($values)
    {
        $transaction = SettingValueModify::getDb()->beginTransaction();

        try {
            foreach ($values as $code => $value) {
                if ($value instanceof SettingValueModify) {
                    $code = $value;
                    $value = $code->value;
                }

                if (!$this->setValue($code, $value)) {
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        } catch (Throwable $e) {
            $transaction->rollBack();

            throw $e;
        }

        return true;
    }

    /**
     * @param SettingValue $model
     *
     * @return mixed
     *
     * @throws InvalidConfigException
     */
    public function typeCast($model)
    {
        $value = $model->value;

        foreach (self::$_typeCasters as $typeCaster) {
            $value = call_user_func($typeCaster, $model);
        }

        $options = self::getOptions($model->setting_code);

        if ($options) {
            $value = $options->typeCast($model, $this);
        }

        return $value;
    }

    /**
     * @return Account|null
     */
    public function getAccount()
    {
        return $this->_account;
    }

    /**
     * @param SettingValue $model
     *
     * @return bool|float|int
     */
    public static function typeCastModel($model)
    {
        switch ($model->setting->type) {
            case 'boolean':
                return (bool) $model->value;

            case 'integer':
                return (integer) $model->value;

            case 'float':
                return (float) $model->value;
        }
    }
}
