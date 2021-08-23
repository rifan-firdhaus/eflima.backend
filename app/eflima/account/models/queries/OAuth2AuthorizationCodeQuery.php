<?php namespace eflima\account\models\queries;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\OAuth2AuthorizationCode;
use eflima\core\db\ActiveQuery;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * This is the ActiveQuery class for [[\eflima\account\models\OAuth2AuthorizationCode]].
 *
 * @see    \eflima\account\models\OAuth2AuthorizationCode
 */
class OAuth2AuthorizationCodeQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     *
     * @return OAuth2AuthorizationCode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     *
     * @return OAuth2AuthorizationCode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
