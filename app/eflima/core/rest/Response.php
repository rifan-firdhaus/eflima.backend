<?php namespace eflima\core\rest;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use stdClass;
use yii\helpers\ArrayHelper;
use yii\web\Response as BaseResponse;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class Response extends BaseResponse
{
    /**
     * @inheritDoc
     */
    protected function prepare()
    {
        if (ArrayHelper::isTraversable($this->data) || $this->data instanceof stdClass) {
            $merged = ArrayHelper::remove($this->data, '_merge', []);

            $this->data = [
                'success' => $this->getIsSuccessful(),
                'status' => [
                    'code' => $this->getStatusCode(),
                    'message' => $this->statusText,
                ],
                'result' => $this->data,
            ];

            $this->data = ArrayHelper::merge($this->data, $merged);
        }

        parent::prepare();
    }
}
