<?php namespace eflima\account\components\oauth2\storages;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\core\models\Account;
use eflima\core\models\OAuth2AccessToken;
use OAuth2\Storage\AccessTokenInterface;
use Yii;
use yii\base\InvalidArgumentException;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AccessToken implements AccessTokenInterface
{

    /**
     * @inheritDoc
     */
    public function getAccessToken($oauth_token)
    {
        $model = OAuth2AccessToken::find()->andWhere(['token' => $oauth_token])->one();

        if (!$model) {
            return;
        }

        return [
            'expires' => $model->expiration,
            'client_id' => $model->client_id,
            'user_id' => $model->account->uuid,
            'scope' => $model->scope,
        ];
    }

    /**
     * @inheritDoc
     */
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null)
    {
        $accountId = Account::find()->andWhere(['uuid' => $user_id])->select('id')->createCommand()->queryScalar();

        if (!$accountId) {
            throw new InvalidArgumentException('User id is invalid');
        }

        $model = new OAuth2AccessToken([
            'token' => $oauth_token,
            'client_id' => $client_id,
            'account_id' => $accountId,
            'expiration' => $expires,
            'scope' => $scope,
        ]);

        if (!$model->save()) {
            Yii::error($model->errors);

            throw new InvalidArgumentException('Failed to save access token');
        }
    }
}
