<?php namespace eflima\control_panel\components\oauth2\storages;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\OAuth2RefreshToken;
use eflima\control_panel\models\Administrator;
use OAuth2\Storage\RefreshTokenInterface;
use Yii;
use yii\base\InvalidArgumentException;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class RefreshToken implements RefreshTokenInterface
{

    /**
     * @inheritDoc
     */
    public function getRefreshToken($refresh_token)
    {
        $model = OAuth2RefreshToken::find()->andWhere(['token' => $refresh_token])->one();

        if (!$model) {
            return;
        }

        $userId = Administrator::find()
            ->andWhere(['account_id' => $model->account_id])
            ->select('uuid')
            ->createCommand()
            ->queryScalar();

        return [
            'refresh_token' => $model->token,
            'expires' => $model->expiration,
            'client_id' => $model->client_id,
            'user_id' => $userId,
            'scope' => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {
        $accountId = Administrator::find()->andWhere(['uuid' => $user_id])->select('account_id')->createCommand()->queryScalar();

        if (!$accountId) {
            throw new InvalidArgumentException('User id is invalid');
        }

        $model = new OAuth2RefreshToken([
            'token' => $refresh_token,
            'client_id' => $client_id,
            'account_id' => $accountId,
            'expiration' => $expires,
        ]);

        if (!$model->save()) {
            Yii::error($model->errors);

            throw new InvalidArgumentException('Failed to save refresh token');
        }
    }

    /**
     * @inheritDoc
     */
    public function unsetRefreshToken($refresh_token)
    {
        $model = OAuth2RefreshToken::find()->andWhere(['token' => $refresh_token])->one();

        if (!$model) {
            throw new InvalidArgumentException('Invalid refresh token');
        }

        $model->is_used = true;

        if (!$model->save(false)) {
            throw new InvalidArgumentException('Failed to save refresh token');
        }
    }
}
