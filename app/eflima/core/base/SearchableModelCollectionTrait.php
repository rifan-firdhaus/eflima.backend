<?php namespace eflima\core\base;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use Exception;
use yii\helpers\ArrayHelper;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
trait SearchableModelCollectionTrait
{
    /**
     * @param array $params
     */
    public function setParams($params = [])
    {
        $this->setAttributes($params);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->getAttributes($this->safeAttributes());
    }

    /**
     * @param $key
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getParam($key)
    {
        return ArrayHelper::getValue($this->attributes, $key);
    }

    /**
     * @param string $key
     * @param mixed  $param
     */
    public function setParam($key, $param)
    {
        $this->setParams([$key => $param]);
    }
}
