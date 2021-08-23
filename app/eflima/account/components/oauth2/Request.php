<?php namespace eflima\account\components\oauth2;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use OAuth2\RequestInterface;
use Yii;
use yii\base\BaseObject;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class Request extends BaseObject implements RequestInterface
{

    public function query($name, $default = null)
    {
        return Yii::$app->request->get($name, $default);
    }

    public function request($name, $default = null)
    {
        return Yii::$app->request->getBodyParam($name, $default);
    }

    public function server($name, $default = null)
    {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
    }

    public function headers($name, $default = null)
    {
        return Yii::$app->request->getHeaders()->get($name, $default);
    }

    public function getAllQueryParameters()
    {
        return Yii::$app->request->queryParams;
    }
}
