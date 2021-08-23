<?php namespace eflima\account\models\queries;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\OAuth2AccessToken;
use eflima\core\db\ActiveQuery;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * This is the ActiveQuery class for [[\eflima\account\models\OAuth2AccessToken]].
 *
 * @see    \eflima\account\models\OAuth2AccessToken
 */
class OAuth2AccessTokenQuery extends ActiveQuery
{
    /**
     * @inheritDoc
     *
     * @return OAuth2AccessToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritDoc
     *
     * @return OAuth2AccessToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
