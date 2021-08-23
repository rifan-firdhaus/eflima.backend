<?php namespace eflima\account\models\queries;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\OAuth2Client;
use eflima\core\db\ActiveQuery;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * This is the ActiveQuery class for [[\eflima\account\models\OAuth2Client]].
 *
 * @see    \eflima\account\models\OAuth2Client
 */
class OAuth2ClientQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     *
     * @return OAuth2Client[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     *
     * @return OAuth2Client|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
