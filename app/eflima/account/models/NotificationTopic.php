<?php namespace eflima\account\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\queries\AccountQuery;
use eflima\account\models\queries\NotificationTopicQuery;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use Throwable;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\StaleObjectException;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 *
 * @property Account $subscriber
 *
 * @property string  $id            [INT UNSIGNED(10)]
 * @property string  $code          [CHAR(64)]
 * @property string  $subscriber_id [INT UNSIGNED(10)]
 * @property string  $subscribed_at [INT UNSIGNED(10)]
 */
class NotificationTopic extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%notification_topic}}';
    }

    /**
     * @inheritDoc
     *
     * @return NotificationTopicQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new NotificationTopicQuery(get_called_class()))->alias("notification_topic");
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'subscribed_at',
            'updatedAtAttribute' => false,
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
            'code' => Yii::t('app', 'Code'),
            'subscriber_id' => Yii::t('app', 'Subscriber ID'),
            'subscribed_at' => Yii::t('app', 'Subscribed At'),
        ];
    }

    /**
     * @return ActiveQuery|AccountQuery
     */
    public function getSubscriber()
    {
        return $this->hasOne(Account::class, ['id' => 'subscriber_id'])->alias('subscriber_of_topic');
    }

    /**
     * @param string             $topic
     * @param Account|string|int $account
     */
    public static function subscribe($topic, $account)
    {
        $accountId = $account instanceof Account ? $account->id : $account;

        if (self::find()->andWhere(['code' => $topic, 'subscriber_id' => $accountId])->exists()) {
            return true;
        }

        $model = new self([
            'code' => $topic,
            'subscriber_id' => $accountId,
        ]);

        return $model->save();
    }

    /**
     * @param string             $topic
     * @param Account|int|string $account
     *
     * @return bool|int
     * @throws Throwable
     * @throws StaleObjectException
     */
    public static function unsubscribe($topic, $account)
    {
        $accountId = $account instanceof Account ? $account->id : $account;

        if (!self::find()->andWhere(['code' => $topic, 'subscriber_id' => $accountId])->exists()) {
            return true;
        }

        $model = self::find()->andWhere(['code' => $topic, 'subscriber_id' => $accountId])->one();

        return $model->delete();
    }
}
