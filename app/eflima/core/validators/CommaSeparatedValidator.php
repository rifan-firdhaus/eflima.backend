<?php namespace eflima\core\validators;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use yii\validators\FilterValidator;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class CommaSeparatedValidator extends FilterValidator
{
    /**
     * @inheritDoc
     */
    public function __construct($config = [])
    {
        $config['filter'] = function ($value) {
            return is_array($value) ? $value : array_filter(explode(',', $value));
        };

        parent::__construct($config);
    }
}
