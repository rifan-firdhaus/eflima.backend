<?php namespace eflima\account\components\oauth2;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\components\oauth2\response_types\AccessToken as AccessTokenResponseType;
use eflima\account\components\oauth2\response_types\AuthorizationCode as AuthorizationCodeResponseType;
use eflima\account\components\oauth2\storages\AccessToken as AccessTokenStorage;
use eflima\account\components\oauth2\storages\AuthorizationCode as AuthorizationCodeStorage;
use eflima\account\components\oauth2\storages\ClientCredentials as ClientCredentialsStorage;
use eflima\account\components\oauth2\storages\RefreshToken as RefreshTokenStorage;
use eflima\account\components\oauth2\storages\UserCredentials as UserCredentialsStorage;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;
use OAuth2\Server;
use Yii;
use yii\base\Component;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property mixed  $accessTokenData
 * @property Server $server
 */
class OAuth2 extends Component
{
    /** @var string|array|RequestInterface */
    public $request = Request::class;

    /** @var string|array|ResponseInterface */
    public $response = Response::class;

    /** @var Server */
    protected $_server;

    public $useJwtAccessTokens = false;
    public $jwtExtraPayloadCallable = null;
    public $storeEncryptedTokenString = true;
    public $useOpenidConnect = false;
    public $idLifetime = 360000;
    public $accessLifetime = 360000;
    public $wwwRealm = 'Service';
    public $tokenParamName = 'access_token';
    public $tokenBearerHeaderName = 'Bearer';
    public $enforceState = true;
    public $requireExactRedirectUri = true;
    public $allowImplicit = false;
    public $allowCredentialsInRequestBody = true;
    public $allowPublicClients = true;
    public $alwaysIssueNewRefreshToken = false;
    public $unsetRefreshTokenAfterUse = true;

    public $grantTypes = [
        'user_credentials' => UserCredentials::class,
        'authorization_code' => AuthorizationCode::class,
        'client_credentials' => ClientCredentials::class,
        'refresh_token' => RefreshToken::class,
    ];

    public $storages = [
        'access_token' => AccessTokenStorage::class,
        'authorization_code' => AuthorizationCodeStorage::class,
        'client_credentials' => ClientCredentialsStorage::class,
        'user_credentials' => UserCredentialsStorage::class,
        'refresh_token' => RefreshTokenStorage::class,
    ];

    public $responTypes = [
        'token' => AccessTokenResponseType::class,
        'code' => AuthorizationCodeResponseType::class,
    ];

    /**
     * @inheritDoc
     */
    public function init()
    {

        $config = [
            'use_jwt_access_tokens' => $this->useJwtAccessTokens,
            'jwt_extra_payload_callable' => $this->jwtExtraPayloadCallable,
            'store_encrypted_token_string' => $this->storeEncryptedTokenString,
            'use_openid_connect' => $this->useOpenidConnect,
            'id_lifetime' => $this->idLifetime,
            'access_lifetime' => $this->accessLifetime,
            'www_realm' => $this->wwwRealm,
            'token_param_name' => $this->tokenParamName,
            'token_bearer_header_name' => $this->tokenBearerHeaderName,
            'enforce_state' => $this->enforceState,
            'require_exact_redirect_uri' => $this->requireExactRedirectUri,
            'allow_implicit' => $this->allowImplicit,
            'allow_credentials_in_request_body' => $this->allowCredentialsInRequestBody,
            'allow_public_clients' => $this->allowPublicClients,
            'always_issue_new_refresh_token' => $this->alwaysIssueNewRefreshToken,
            'unset_refresh_token_after_use' => $this->unsetRefreshTokenAfterUse,
        ];

        foreach ($this->storages as $key => $storage) {
            $this->storages[$key] = new $storage();
        }


        $this->responTypes['code'] = new $this->responTypes['code']($this->storages['authorization_code']);
        $this->responTypes['token'] = new $this->responTypes['token']($this->storages['access_token'], (isset($this->storages['refresh_token'])) ? $this->storages['refresh_token'] : null);

        foreach ($this->grantTypes as $key => $grantType) {
            $grantTypeStorage = $this->storages[$key];

            $this->grantTypes[$key] = new $grantType($grantTypeStorage);
        }

        $this->_server = new Server($this->storages, $config, $this->grantTypes, $this->responTypes);

        $this->request = Yii::createObject($this->request);
        $this->response = Yii::createObject($this->response);

        parent::init();
    }

    public function handleUserInfoRequest()
    {
        return $this->server->handleUserInfoRequest($this->request, $this->response);
    }

    public function handleTokenRequest()
    {
        return $this->server->handleTokenRequest($this->request, $this->response);
    }

    public function grantAccessToken()
    {
        return $this->server->grantAccessToken($this->request, $this->response);
    }

    public function handleRevokeRequest()
    {
        return $this->server->grantAccessToken($this->request, $this->response);

    }

    public function handleAuthorizeRequest($isAuthorized, $userId = null)
    {
        return $this->server->handleAuthorizeRequest($this->request, $this->response, $isAuthorized, $userId);
    }

    public function validateAuthorizeRequest()
    {
        return $this->server->validateAuthorizeRequest($this->request, $this->response);
    }

    public function verifyResourceRequest($scope = null)
    {
        return $this->server->verifyResourceRequest($this->request, $this->response, $scope);
    }

    public function getAccessTokenData()
    {
        return $this->server->getAccessTokenData($this->request, $this->response);
    }

    /**
     * @return Server
     */
    public function getServer()
    {
        return $this->_server;
    }
}
