<?php namespace eflima\core\validators;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use yii\validators\FilterValidator;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class DateModifierValidator extends FilterValidator
{
    const START = 'START';
    const END = 'END';

    /** @var string */
    public $modifier = self::START;

    /**
     * @inheritDoc
     */
    public function __construct($config = [])
    {
        $config['filter'] = function ($value) {
            if ($this->modifier === self::END) {
                return strtotime(date('Y-m-d 23:59:59', $value));
            }

            return strtotime(date('Y-m-d 00:00:00', $value));
        };

        parent::__construct($config);
    }
}
