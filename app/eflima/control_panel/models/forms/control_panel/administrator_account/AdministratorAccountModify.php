<?php namespace eflima\control_panel\models\forms\control_panel\administrator_account;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\control_panel\models\AdministratorAccount;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AdministratorAccountModify extends AdministratorAccount
{
    public $password_repeat;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [
                ['username', 'email'],
                'required',
            ],
            [
                ['username', 'email', 'password', 'phone', 'password_repeat'],
                'string',
            ],
            [
                ['username', 'email'],
                'unique',
            ],
            [
                ['email'],
                'email',
            ],
            [
                ['password'],
                'string',
                'min' => 8,
            ],
            [
                ['username'],
                'string',
                'min' => 3,
                'max' => 48,
            ],
            [
                ['password'],
                'required',
                'when' => function ($model) {
                    /** @var AdministratorAccountModify $model */

                    return $model->getIsNewRecord();
                },
            ],
            [
                ['password_repeat'],
                'compare',
                'compareAttribute' => 'password',
            ],
            [
                ['password_repeat'],
                'required',
                'when' => function ($model) {
                    return !empty($model->password);
                },
            ],
        ];
    }
}
