<?php namespace eflima\account\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\queries\AccountQuery;
use eflima\account\models\queries\NotificationQuery;
use eflima\account\models\queries\NotificationReceiptQuery;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use Yii;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 *
 * @property Account      $account
 * @property Notification $notification
 *
 * @property string       $id              [INT UNSIGNED(10)]
 * @property string       $notification_id [INT UNSIGNED(10)]
 * @property string       $account_id      [INT UNSIGNED(10)]
 * @property string       $read_at         [INT UNSIGNED(10)]
 * @property string       $opened_at       [INT UNSIGNED(10)]
 */
class NotificationReceipt extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%notification_receipt}}';
    }

    /**
     * @inheritDoc
     *
     * @return NotificationReceiptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new NotificationReceiptQuery(get_called_class()))->alias("notification_receipt");
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'notification_id' => Yii::t('app', 'Notification ID'),
            'account_id' => Yii::t('app', 'Account ID'),
            'read_at' => Yii::t('app', 'Read At'),
            'opened_at' => Yii::t('app', 'Opened At'),
        ];
    }

    /**
     * @return ActiveQuery|AccountQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id'])->alias('account_of_notification_receipt');
    }

    /**
     * @return ActiveQuery|NotificationQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notification::class, ['id' => 'notification_id'])->alias('notification_of_receipt');
    }
}
