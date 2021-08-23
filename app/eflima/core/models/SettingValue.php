<?php namespace eflima\core\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\Account;
use eflima\account\models\queries\AccountQuery;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use eflima\core\models\queries\SettingQuery;
use eflima\core\models\queries\SettingValueQuery;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 *
 * @property Account $account
 * @property Setting $setting
 *
 * @property int     $id           [int(10) unsigned]
 * @property string  $setting_code [varchar(255)]
 * @property int     $account_id   [int(11) unsigned]
 * @property string  $value
 * @property int     $updated_at   [int(11)]
 */
class SettingValue extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%setting_value}}';
    }

    /**
     * @inheritDoc
     *
     * @return SettingValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new SettingValueQuery(get_called_class()))->alias("setting_value");
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => false,
        ];

        return $behaviors;
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'account_id' => Yii::t('app', 'Account ID'),
            'value_file' => Yii::t('app', 'Value File'),
        ];
    }

    /**
     * @return ActiveQuery|AccountQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id']);
    }

    /**
     * @return ActiveQuery|SettingQuery
     */
    public function getSetting()
    {
        return $this->hasOne(Setting::class, ['code' => 'setting_code'])->inverseOf('values');
    }
}
