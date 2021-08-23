<?php namespace eflima\account\models\queries;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\Notification;
use eflima\account\models\NotificationReceiver;
use eflima\account\models\NotificationTopic;
use eflima\core\db\ActiveQuery;
use eflima\core\db\FindByUUIDQQuery;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * This is the ActiveQuery class for [[\eflima\account\models\Notification]].
 *
 * @see    \eflima\account\models\Notification
 */
class NotificationQuery extends ActiveQuery
{
    use FindByUUIDQQuery;

    /**
     * @inheritDoc
     *
     * @return Notification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritDoc
     *
     * @return Notification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $accountId
     *
     * @return $this
     */
    public function receiver($accountId)
    {
        $this->leftJoin(NotificationReceiver::tableName(), [
            'AND',
            ['notification_receiver.receiver_id' => $accountId],
            "[[notification_receiver.notification_id]] = [[{$this->getAlias()}.id]]",
        ]);

        $this->leftJoin(NotificationTopic::tableName(), [
            'AND',
            ['notification_topic.subscriber_id' => $accountId],
            "[[notification_topic.code]] = [[{$this->getAlias()}.topic_code]]",
        ]);

        $this->andWhere([
            'OR',
            ['IS NOT', 'notification_receiver.id', null],
            ['IS NOT', 'notification_topic.id', null],
        ]);

        return $this;
    }
}
