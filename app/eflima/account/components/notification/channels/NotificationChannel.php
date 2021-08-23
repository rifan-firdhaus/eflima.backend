<?php namespace eflima\account\components\notification\channels;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\Account;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
abstract class NotificationChannel
{
    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var array */
    public $data;

    /** @var Account[]|string[] */
    public $receivers = [];

    /** @var Account */
    public $sender;

    /**
     * @return boolean
     */
    abstract public function send();
}
