<?php namespace eflima\account\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\queries\NotificationQuery;
use eflima\account\models\queries\NotificationReceiverQuery;
use eflima\account\models\queries\NotificationTopicQuery;
use eflima\core\behaviors\UuidAttributeBehavior;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use Throwable;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception as DbException;
use yii\helpers\Json;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 *
 * @property NotificationReceipt[]  $receipts
 * @property NotificationReceiver[] $receivers
 * @property NotificationTopic      $topic
 *
 * @property string                 $id         [INT UNSIGNED(10)]
 * @property string                 $uuid       [CHAR(36)]
 * @property string                 $topic_code [CHAR(64)]
 * @property string                 $title      [TEXT(65535)]
 * @property string                 $image      [TEXT(65535)]
 * @property string                 $content    [TEXT(65535)]
 * @property string                 $type       [CHAR(64)]
 * @property string                 $data       [TEXT(65535)]
 * @property string                 $at         [INT UNSIGNED(10)]
 */
class Notification extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * @inheritDoc
     *
     * @return NotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new NotificationQuery(get_called_class()))->alias("notification");
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'at',
            'updatedAtAttribute' => false,
        ];

        $behaviors['uuid'] = [
            'class' => UuidAttributeBehavior::class,
        ];

        return $behaviors;
    }

    /**
     * @return ActiveQuery|NotificationReceiverQuery
     */
    public function getReceipts()
    {
        return $this->hasMany(NotificationReceipt::class, ['notification_id' => 'id']);
    }

    /**
     * @return ActiveQuery|NotificationReceiverQuery
     */
    public function getReceivers()
    {
        return $this->hasMany(NotificationReceiver::class, ['notification_id' => 'id']);
    }

    /**
     * @return ActiveQuery|NotificationTopicQuery
     */
    public function getTopic()
    {
        return $this->hasOne(NotificationTopic::class, ['code' => 'topic_code'])->alias('topic_of_notification');
    }

    /**
     * @inheritDoc
     */
    public function normalizeAttributesOnSave()
    {
        if ($this->data && is_array($this->data)) {
            $this->data = Json::encode($this->data);
        }

        parent::normalizeAttributesOnSave();
    }

    /**
     * @inheritDoc
     */
    public function normalizeAttributesOnRead()
    {
        if ($this->data && is_string($this->data)) {
            $this->data = Json::decode($this->data);
        }

        parent::normalizeAttributesOnRead();
    }

    /**
     * @param Account[]|string[] $receivers
     * @param array              $attributes
     *
     * @return bool
     * @throws Throwable
     * @throws DbException
     */
    public static function send($receivers, $attributes)
    {
        $transaction = self::getDb()->beginTransaction();

        try {
            $notification = new Notification();
            $notification->setAttributes($attributes, false);

            if (!$notification->save()) {
                return false;
            }

            $receiverDBData = [];

            foreach ($receivers as $receiver) {
                $receiverDBData[] = [
                    $notification->id,
                    $receiver instanceof Account ? $receiver->id : $receiver,
                ];
            }

            self::getDb()->createCommand()
                ->batchInsert(NotificationReceiver::tableName(), ['notification_id', 'receiver_id'], $receiverDBData)
                ->execute();

            $transaction->commit();
        } catch (Throwable $exception) {
            $transaction->rollBack();

            throw $exception;
        }

        return true;
    }
}
