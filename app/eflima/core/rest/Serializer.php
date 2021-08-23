<?php namespace eflima\core\rest;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use yii\base\Model;
use yii\rest\Serializer as BaseSerializer;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class Serializer extends BaseSerializer
{

    /**
     * @param Model $model
     *
     * @return array
     */
    protected function internalSerializeModelErrors($model)
    {
        $result = [];

        foreach ($model->errors as $name => $messages) {
            $_result = [];

            foreach ($messages as $message) {
                if ($message instanceof Model) {
                    $_result = $this->internalSerializeModelErrors($message);
                } elseif ($message instanceof ModelCollection) {
                    foreach ($message->models as $key => $value) {
                        $_result[] = $value instanceof Model ? $this->internalSerializeModelErrors($value) : $value;
                    }
                } else {
                    $_result[] = $message;
                }
            }

            $result[$name] = $_result;
        }

        return $result;
    }


    /**
     * @inheritDoc
     */
    protected function serializeModelErrors($model)
    {
        $this->response->setStatusCode(422, 'Data Validation Failed.');

        return $this->internalSerializeModelErrors($model);
    }
}
