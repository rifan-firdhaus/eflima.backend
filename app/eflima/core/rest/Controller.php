<?php namespace eflima\core\rest;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use Yii;
use yii\base\Arrayable;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\Controller as YiiRestController;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class Controller extends YiiRestController
{
    /** @var Serializer */
    public $serializer = Serializer::class;

    public $messages = [];
    public $success = true;
    public $serializeResult = true;

    const MESSAGE_CATEGORY_NOTIFICATION = 'notification';
    const MESSAGE_CATEGORY_SUCCESS = 'success';
    const MESSAGE_CATEGORY_ERROR = 'error';
    const MESSAGE_CATEGORY_WARNING = 'warning';

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['http://127.0.0.1:4200', 'http://127.0.0.1:4400'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['content-type', 'authorization'],
            ],
        ];

        if (Yii::$app->getUser()->getIsGuest()) {
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::class,
            ];
        }

        return $behaviors;
    }

    /**
     * @param null|string $message
     *
     * @return $this
     */
    public function success($message = null)
    {
        $this->success = true;

        if ($message !== null) {
            $this->addMessage(self::MESSAGE_CATEGORY_SUCCESS, $message);
        }

        return $this;
    }

    /**
     * @param null|string $message
     *
     * @return $this
     */
    public function failed($message = null)
    {
        $this->success = false;

        if ($message !== null) {
            $this->addMessage(self::MESSAGE_CATEGORY_ERROR, $message);
        }

        return $this;
    }

    /**
     * @param string $message
     * @param string $category
     *
     * @return $this
     */
    public function addMessage($message, $category = self::MESSAGE_CATEGORY_NOTIFICATION)
    {
        $this->messages[$category] = $message;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function serializeData($data)
    {
        if (!$this->serializeResult) {
            return $data;
        }

        $parsedData = [
            'messages' => $this->messages,
            'result' => parent::serializeData($data),
        ];

        if ($data instanceof Model && $data->hasErrors()) {
            $parsedData['type'] = 'model-errors';
        } elseif ($data instanceof Arrayable) {
            $parsedData['type'] = $data instanceof Model ? 'model' : 'data';
        } elseif ($data instanceof DataProviderInterface) {
            $parsedData['type'] = $data instanceof ActiveDataProvider ? 'model-list' : 'data-list';

            if (($pagination = $data->getPagination())) {
                $parsedData['pagination'] = [
                    'total_count' => $pagination->totalCount,
                    'page_count' => $pagination->getPageCount(),
                    'current_page' => $pagination->getPage() + 1,
                    'page_size' => $pagination->pageSize,
                    'links' => $pagination->getLinks(true),
                ];
            }
        } else {
            $parsedData['type'] = 'raw';

            if (ArrayHelper::isAssociative($data)) {
                $parsedData['type'] = 'associative-array';
            } elseif (is_array($data)) {
                $parsedData['type'] = 'array';
            }
        }

        return ['_merge' => $parsedData];
    }
}
