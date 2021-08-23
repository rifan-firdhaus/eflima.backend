<?php namespace eflima\core\db;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
trait FindByUUIDQQuery
{
    /**
     * @param string $uuid
     *
     * @return $this
     */
    public function uuid($uuid)
    {
        return $this->andWhere(["{$this->getAlias()}.uuid" => $uuid]);
    }
}
