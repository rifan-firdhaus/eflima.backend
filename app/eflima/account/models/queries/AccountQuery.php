<?php namespace eflima\account\models\queries;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\Account;
use eflima\core\db\ActiveQuery;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * This is the ActiveQuery class for [[\eflima\account\models\Account]].
 *
 * @see    \eflima\account\models\Account
 */
class AccountQuery extends ActiveQuery
{
    /**
     * @param string $type
     *
     * @return AccountQuery
     */
    public function type($type)
    {
        return $this->andWhere(["{$this->getAlias()}.type" => $type]);
    }

    /**
     * @inheritDoc
     *
     * @return Account[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritDoc
     *
     * @return Account|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
