<?php namespace eflima\core\base;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use yii\data\BaseDataProvider;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
interface SearchableCollection
{
    /**
     * @return BaseDataProvider
     */
    public function asDataProvider();

    /**
     * @return array
     */
    public function asCollection();

    /**
     * @return array|mixed
     */
    public function getParams();

    /**
     * @param $key
     *
     * @return string mixed
     */
    public function getParam($key);

    /**
     * @param array $params
     *
     * @return void
     */
    public function setParams($params = []);

    /**
     * @param string $key
     * @param mixed  $param
     *
     * @return void
     */
    public function setParam($key, $param);

    /**
     * @return bool
     */
    public function filter();
}
