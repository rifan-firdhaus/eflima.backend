<?php namespace eflima\account\components\notification\channels;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\Notification;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class DatabaseChannel extends NotificationChannel
{
    /** @var string */
    public $topic;

    /** @var string */
    public $type;

    public function send()
    {
        Notification::send($this->receivers, [
            'title' => $this->title,
            'content' => $this->content,
            'data' => $this->data,
            'topic_code' => $this->topic,
            'type' => $this->type,
        ]);
    }
}
