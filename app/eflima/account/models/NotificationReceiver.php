<?php namespace eflima\account\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\queries\NotificationQuery;
use eflima\account\models\queries\NotificationReceiverQuery;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use Yii;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property Notification $notification
 * @property Account      $receiver
 *
 * @property string       $id              [INT UNSIGNED(10)]
 * @property string       $notification_id [INT UNSIGNED(10)]
 * @property string       $receiver_id     [INT UNSIGNED(10)]
 */
class NotificationReceiver extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%notification_receiver}}';
    }

    /**
     * @inheritDoc
     *
     * @return NotificationReceiverQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new NotificationReceiverQuery(get_called_class()))->alias("notification_receiver");
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'notification_id' => Yii::t('app', 'Notification ID'),
            'receiver_id' => Yii::t('app', 'Receiver ID'),
        ];
    }

    /**
     * @return ActiveQuery|NotificationQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notification::class, ['id' => 'notification_id'])->alias('notification_of_receiver');
    }

    /**
     * @return ActiveQuery|NotificationReceiverQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(Account::class, ['id' => 'receiver_id'])->alias('receiver_of_notification');
    }
}
