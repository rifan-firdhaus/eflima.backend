<?php namespace eflima\control_panel\models\forms\control_panel\administrator;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\control_panel\models\Administrator;
use eflima\core\base\SearchableActiveRecordCollection;
use eflima\core\base\SearchableActiveRecordCollectionTrait;
use eflima\core\validators\DateModifierValidator;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AdministratorCollection extends Model implements SearchableActiveRecordCollection
{
    use SearchableActiveRecordCollectionTrait;

    public $id;
    public $q;
    public $is_blocked;
    public $registered_at_from;
    public $registered_at_to;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [
                ['q'],
                'safe',
            ],
            [
                'id',
                'commaSeparated',
            ],
            [
                ['registered_at_from', 'registered_at_to'],
                'date',
                'format' => 'php:U',
            ],
            [
                ['registered_at_from'],
                'dateModifier',
                'modifier' => DateModifierValidator::START,
                'skipOnEmpty' => true,
            ],
            [
                ['registered_at_to'],
                'dateModifier',
                'modifier' => DateModifierValidator::END,
                'skipOnEmpty' => true,
            ],
            [
                ['is_blocked'],
                'boolean',
                'skipOnEmpty' => true,
            ],
        ];
    }

    /** @var array */
    public $dataProvider = [
        'class' => ActiveDataProvider::class,
        'sort' => [
            'attributes' => [
                'name',
                'account.username' => [
                    'asc' => ['account_of_administrator.username' => SORT_ASC],
                    'desc' => ['account_of_administrator.username' => SORT_DESC],
                ],
                'account.email' => [
                    'asc' => ['account_of_administrator.email' => SORT_ASC],
                    'desc' => ['account_of_administrator.email' => SORT_DESC],
                ],
                'account.phone' => [
                    'asc' => ['account_of_administrator.phone' => SORT_ASC],
                    'desc' => ['account_of_administrator.phone' => SORT_DESC],
                ],
                'account.registered_at' => [
                    'asc' => ['account_of_administrator.registered_at' => SORT_ASC],
                    'desc' => ['account_of_administrator.registered_at' => SORT_DESC],
                ],
                'account.is_blocked' => [
                    'asc' => ['account_of_administrator.is_blocked' => SORT_ASC],
                    'desc' => ['account_of_administrator.is_blocked' => SORT_DESC],
                ],
            ],
        ],
    ];

    /**
     * @inheritDoc
     */
    public function filter()
    {
        if (!$this->validate()) {
            return false;
        }

        $query = $this->getQuery();

        $query->joinWith('account');

        $query->andFilterWhere(['account_of_administrator.is_blocked' => $this->is_blocked])
            ->andFilterWhere(['administrator.uuid' => $this->id])
            ->andFilterWhere(['>=', 'account_of_administrator.registered_at', $this->registered_at_from])
            ->andFilterWhere(['<=', 'account_of_administrator.registered_at', $this->registered_at_to])
            ->andFilterWhere([
                'OR',
                ['LIKE', 'account_of_administrator.username', $this->q],
                ['LIKE', 'account_of_administrator.email', $this->q],
                ['LIKE', 'account_of_administrator.phone', $this->q],
                ['LIKE', 'administrator.name', $this->q],
            ]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        if (!$this->_query) {
            $this->_query = Administrator::find();
        }

        return $this->_query;
    }
}
