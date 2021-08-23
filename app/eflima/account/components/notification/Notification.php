<?php namespace eflima\account\components\notification;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\components\notification\channels\NotificationChannel;
use eflima\account\models\Account;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class Notification extends Component
{
    /** @var string */
    public $content;

    /** @var string */
    public $title;

    /** @var Account[]|string[] */
    public $receivers = [];

    /** @var array */
    public $data;

    /** @var Account */
    public $sender;

    /**
     * @param array $channels
     *
     * @throws InvalidConfigException
     */
    public function send($channels)
    {
        foreach ($channels as $channelParams) {
            if (!is_array($channelParams)) {
                $channelParams = ['class' => $channelParams];
            }

            $channelParams = array_merge([
                'content' => $this->content,
                'title' => $this->title,
                'receivers' => $this->receivers,
                'data' => $this->data,
                'sender' => $this->sender,
            ], $channelParams);

            /** @var NotificationChannel $channel */
            $channel = Yii::createObject($channelParams);


            $channel->send();
        }
    }
}
