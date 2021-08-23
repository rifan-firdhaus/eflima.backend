<?php

namespace eflima\control_panel\models;

use eflima\account\models\Account;
use eflima\account\models\queries\AccountQuery;
use eflima\control_panel\models\queries\AdministratorQuery;
use eflima\core\behaviors\UuidAttributeBehavior;
use eflima\core\db\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int          $id         [int(10) unsigned]
 * @property string       $uuid       [char(36)]
 * @property int          $account_id [int(11) unsigned]
 * @property string       $name
 *
 * @property-read Account $account
 */
class Administrator extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%administrator}}';
    }

    /**
     * @inheritDoc
     * @return AdministratorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new AdministratorQuery(get_called_class()))->alias('administrator');
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['uuid'] = [
            'class' => UuidAttributeBehavior::class,
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
            'uuid' => Yii::t('app', 'UUID'),
            'account_id' => Yii::t('app', 'Account'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function fields()
    {
        $fields = parent::fields();

        unset(
            $fields['id'],
            $fields['account_id']
        );

        return $fields;
    }

    /**
     * @inheritDoc
     */
    public function extraFields()
    {
        return [
            'account',
        ];
    }

    /**
     * @inheritDoc
     */
    public function normalizeAttributesOnSave()
    {
        parent::normalizeAttributesOnSave();
    }

    /**
     * @return ActiveQuery|AccountQuery
     */
    public function getAccount()
    {
        return $this->hasOne(AdministratorAccount::class, ['id' => 'account_id'])->alias('account_of_administrator');
    }
}
