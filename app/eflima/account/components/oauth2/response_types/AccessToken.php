<?php namespace eflima\account\components\oauth2\response_types;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use OAuth2\ResponseType\AccessToken as BaseAccessToken;
use Yii;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @inheritDoc
     */
    protected function generateAccessToken()
    {
        return Yii::$app->security->generateRandomString(64);
    }
}
