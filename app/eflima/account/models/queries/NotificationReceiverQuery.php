<?php namespace eflima\account\models\queries;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\NotificationReceiver;
use eflima\core\db\ActiveQuery;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * This is the ActiveQuery class for [[\eflima\account\models\NotificationReceiver]].
 *
 * @see    \eflima\account\models\NotificationReceiver
 */
class NotificationReceiverQuery extends ActiveQuery
{
    /**
     * @inheritDoc
     *
     * @return NotificationReceiver[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritDoc
     *
     * @return NotificationReceiver|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
