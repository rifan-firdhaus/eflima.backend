<?php namespace eflima\account\components\oauth2\storages;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\OAuth2Client;
use OAuth2\Storage\ClientCredentialsInterface;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class ClientCredentials extends BaseObject implements ClientCredentialsInterface
{

    /**
     * @inheritDoc
     */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
        $model = $this->getClientModel($client_id);

        if ($model->is_public) {
            return true;
        }

        return $model->client_secret === $client_secret;
    }

    /**
     * @inheritDoc
     */
    public function isPublicClient($client_id)
    {
        $model = $this->getClientModel($client_id);

        return $model->is_public;
    }

    /**
     * @inheritDoc
     */
    public function getClientDetails($client_id)
    {
        $model = $this->getClientModel($client_id);

        return [
            'client_id' => $model->id,
            'redirect_uri' => '',
        ];
    }

    /**
     * TODO: need further implementation
     *
     * @inheritDoc
     */
    public function getClientScope($client_id)
    {
        return;
    }

    /**
     * TODO: need further implementation
     *
     * @inheritDoc
     */
    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        return true;
    }

    /**
     * @param string $client_id
     *
     * @return array|OAuth2Client
     */
    protected function getClientModel($client_id)
    {
        $model = OAuth2Client::find()->andWhere(['id' => $client_id])->one();

        if (!$model) {
            throw new InvalidArgumentException('Client ID is not registered');
        }

        return $model;
    }
}
