<?php namespace eflima\control_panel\controllers\control_panel;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\control_panel\models\Administrator;
use eflima\control_panel\models\AdministratorAccount;
use eflima\control_panel\models\forms\control_panel\administrator\AdministratorCollection;
use eflima\control_panel\models\forms\control_panel\administrator\AdministratorLogin;
use eflima\control_panel\models\forms\control_panel\administrator\AdministratorModify;
use eflima\control_panel\models\forms\control_panel\administrator_account\AdministratorAccountModify;
use eflima\control_panel\rest\Controller;
use Exception;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\ConflictHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AdministratorController extends Controller
{
    /**
     * @inheritDoc
     */
    public function verbs()
    {
        return [
            'login' => ['POST', 'OPTIONS'],
            'get' => ['GET', 'OPTIONS'],
            'collection' => ['GET', 'OPTIONS'],
            'create' => ['POST', 'OPTIONS'],
            'update' => ['PUT', 'OPTIONS'],
            'block' => ['PATCH', 'OPTIONS'],
            'unblock' => ['PATCH', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (isset($behaviors['authenticator'])) {
            $behaviors['authenticator']['optional'] = [
                'login',
            ];
        }

        return $behaviors;
    }

    /**
     * @param string|string[]      $uuid
     * @param string|Administrator $modelClass
     *
     * @return Administrator|Administrator[]
     * @throws NotFoundHttpException
     */
    protected function getModelByUUID($uuid, $modelClass = Administrator::class)
    {
        $query = $modelClass::find()->uuid($uuid);

        if (is_string($uuid)) {
            $model = $query->one();

            if (!$model) {
                throw new NotFoundHttpException("Admin with id: {$uuid} doesn't exists");
            }

            return $model;
        }

        $models = $query->all();

        if (count($uuid) > count($models)) {
            throw new NotFoundHttpException("Some admin doesn't exists");
        }

        return $models;
    }


    /**
     * @return array|AdministratorLogin|bool
     * @throws InvalidConfigException
     */
    public function actionLogin()
    {
        $model = new AdministratorLogin();

        $model->load($this->request->getBodyParams(), '');

        if ($result = $model->login()) {
            return $result;
        }

        if ($model->hasErrors()) {
            return $model;
        }

        return false;
    }

    /**
     * @return Administrator
     * @throws NotFoundHttpException
     * @throws Throwable
     */
    public function actionProfile()
    {
        /** @var AdministratorAccount $account */
        $account = Yii::$app->getUser()->getIdentity();

        return $this->getModelByUUID($account->profile->uuid);
    }


    /**
     * @param null|string $id
     *
     * @return array|Administrator
     * @throws NotFoundHttpException
     */
    public function actionGet($id = null)
    {
        return !$id ? $this->actionCollection() : $this->getModelByUUID($id);
    }

    /**
     * @return Administrator[]|ActiveDataProvider|AdministratorCollection
     */
    public function actionCollection()
    {
        $searchModel = new AdministratorCollection();

        $searchModel->setParams($this->request->getQueryParams());

        if (!$searchModel->filter()) {
            return $searchModel;
        }

        if ((bool) $this->request->getQueryParam('as_collection', false)) {
            return $searchModel->asCollection();
        }

        return $searchModel->asDataProvider();
    }

    /**
     * @param null|string $id
     *
     * @return AdministratorModify
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionModify($id = null)
    {
        return !$id ? $this->actionCreate() : $this->actionUpdate($id);
    }

    /**
     * @param AdministratorModify|Administrator $model
     *
     * @return AdministratorModify|object
     * @throws InvalidConfigException
     */
    private function modify($model)
    {
        $model->load($this->request->getBodyParams(), '');
        $model->accountModel->load($this->request->getBodyParams(), 'account');

        // Validation request
        if ($this->request->get('validate', false)) {
            return $this->validateModel($model);
        }

        if ($model->save()) {
            $model->refresh();
        }

        return $model;
    }

    /**
     * @param AdministratorModify $model
     *
     * @return object
     * @throws Exception
     */
    public function validateModel($model)
    {
        if ($model->validate($this->request->get('attribute'))) {
            return (object) [];
        }

        if (!$this->request->get('attribute')) {
            return $model;
        }

        $result = $this->serializeData($model)['_merge'];

        $result = ArrayHelper::getValue($result['result'], $this->request->get('attribute'));

        $this->serializeResult = false;

        $this->response->setStatusCode(200);

        return $result ? $result : (object) [];
    }

    /**
     * @return AdministratorModify
     * @throws InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new AdministratorModify();
        $model->accountModel = new AdministratorAccountModify();

        return $this->modify($model);
    }

    /**
     * @param string $id
     *
     * @return AdministratorModify
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        /** @var AdministratorModify $model */
        $model = $this->getModelByUUID($id, AdministratorModify::class);
        $model->accountModel = AdministratorAccountModify::findOne($model->account_id);

        return $this->modify($model);
    }

    /**
     * @param string $id
     * @param bool   $bulk
     *
     * @return array|mixed
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete($id, $bulk = false)
    {
        $id = $bulk ? ArrayHelper::commaSeparated($id) : [$id];
        $models = $this->getModelByUUID($id);

        return Administrator::getDb()->transaction(function () use ($models, $bulk) {
            $result = [];

            foreach ($models as $model) {
                $result[] = $this->delete($model);
            }

            return !$bulk ? $result[0] : $result;
        });
    }

    /**
     * @param Administrator $model
     *
     * @throws ServerErrorHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function delete($model)
    {
        if (!$model->delete()) {
            throw new ServerErrorHttpException("Failed to delete admin with id: {$model->id}");
        }

        return $model;
    }

    /**
     * @param string $id
     * @param bool   $bulk
     *
     * @return Administrator|Administrator[]
     * @throws ConflictHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws Throwable
     */
    public function actionBlock($id, $bulk = false)
    {
        $id = $bulk ? ArrayHelper::commaSeparated($id) : [$id];
        $models = $this->getModelByUUID($id);

        return Administrator::getDb()->transaction(function () use ($models, $bulk) {
            $result = [];

            foreach ($models as $model) {
                $result[] = $this->block($model);
            }

            return !$bulk ? $result[0] : $result;
        });
    }

    /**
     * @param Administrator $model
     *
     * @return mixed
     * @throws ConflictHttpException
     * @throws ServerErrorHttpException
     */
    public function block($model)
    {
        if ($model->account->is_blocked) {
            throw new ConflictHttpException("Admin has already blocked");
        }

        if (!$model->account->block()) {
            throw new ServerErrorHttpException("Failed to block admin with id: {$model->id}");
        }

        return $model;
    }

    /**
     * @param string $id
     * @param bool   $bulk
     *
     * @return Administrator|Administrator[]
     * @throws ConflictHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws Throwable
     */
    public function actionUnblock($id, $bulk = false)
    {
        $id = $bulk ? ArrayHelper::commaSeparated($id) : [$id];
        $models = $this->getModelByUUID($id);

        return Administrator::getDb()->transaction(function () use ($models, $bulk) {
            $result = [];

            foreach ($models as $model) {
                $result[] = $this->unblock($model);
            }

            return !$bulk ? $result[0] : $result;
        });
    }

    /**
     * @param Administrator $model
     *
     * @return mixed
     * @throws ConflictHttpException
     * @throws ServerErrorHttpException
     */
    public function unblock($model)
    {
        if (!$model->account->is_blocked) {
            throw new ConflictHttpException("Admin is not blocked");
        }

        if (!$model->account->unblock()) {
            throw new ServerErrorHttpException("Failed to unblock admin with id: {$model->id}");
        }

        return $model;
    }
}
