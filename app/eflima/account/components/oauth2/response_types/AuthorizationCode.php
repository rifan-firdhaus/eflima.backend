<?php namespace eflima\account\components\oauth2\response_types;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use OAuth2\ResponseType\AuthorizationCode as BaseCode;
use Yii;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AuthorizationCode extends BaseCode
{
    /**
     * @inheritDoc
     */
    protected function generateAuthorizationCode()
    {
        return Yii::$app->security->generateRandomString(32);
    }
}
