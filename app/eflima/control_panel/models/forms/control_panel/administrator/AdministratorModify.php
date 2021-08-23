<?php namespace eflima\control_panel\models\forms\control_panel\administrator;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\control_panel\models\Administrator;
use eflima\control_panel\models\forms\control_panel\administrator_account\AdministratorAccountModify;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AdministratorModify extends Administrator
{
    /** @var AdministratorAccountModify */
    public $accountModel;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [
                ['name'],
                'required',
            ],
            [
                ['name'],
                'string',
                'min' => 10,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function afterValidate()
    {
        $this->accountModel->validate();

        if ($this->accountModel->hasErrors()) {
            $this->addError("account", $this->accountModel);
        }

        parent::afterValidate();
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!$this->accountModel->save(false)) {
            return false;
        }

        $this->account_id = $this->accountModel->id;

        return true;
    }
}
