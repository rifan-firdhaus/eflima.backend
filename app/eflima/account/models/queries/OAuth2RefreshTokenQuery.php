<?php namespace eflima\account\models\queries;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\OAuth2RefreshToken;
use eflima\core\db\ActiveQuery;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * This is the ActiveQuery class for [[OAuth2RefreshToken]].
 *
 * @see    OAuth2RefreshToken
 */
class OAuth2RefreshTokenQuery extends ActiveQuery
{
    /**
     * @inheritDoc
     *
     * @return OAuth2RefreshToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritDoc
     *
     * @return OAuth2RefreshToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
