<?php namespace eflima\control_panel\components\oauth2\storages;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\OAuth2AuthorizationCode;
use eflima\control_panel\models\Administrator;
use OAuth2\Storage\AuthorizationCodeInterface;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\db\Exception;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AuthorizationCode extends BaseObject implements AuthorizationCodeInterface
{

    /**
     * @inheritDoc
     */
    public function getAuthorizationCode($code)
    {
        $model = OAuth2AuthorizationCode::find()->andWhere(['code' => $code])
            ->andWhere(['>=', 'expiration', $code])
            ->one();

        if (!$model) {
            return;
        }

        $userId = Administrator::find()
            ->andWhere(['account_id' => $model->account_id])
            ->select('uuid')
            ->createCommand()
            ->queryScalar();

        return [
            'client_id' => $model->client_id,
            'user_id' => $userId,
            'redirect_uri' => $model->redirect_uri,
            'expires' => $model->expiration,
        ];
    }

    /**
     * @inheritDoc
     */
    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
    {
        $model = new OAuth2AuthorizationCode([
            'code' => $code,
            'client_id' => $client_id,
            'account_id' => $user_id,
            'redirect_uri' => $redirect_uri,
            'expiration' => $expires,
        ]);

        if (!$model->save()) {
            Yii::error($model->errors);

            throw new InvalidArgumentException('Failed to save authorization code');
        }
    }

    /**
     * @inheritDoc
     */
    public function expireAuthorizationCode($code)
    {
        $model = OAuth2AuthorizationCode::find()->andWhere(['code' => $code])->one();

        if (!$model) {
            throw new InvalidArgumentException('Auth Code doesn\'t exists');
        }

        $model->is_used = true;
        $model->used_at = time();

        if (!$model->save(false)) {
            throw new Exception('Failed to save authorization code');
        }
    }
}
