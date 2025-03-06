<?php

namespace imarc\databackup\controllers;

use Craft;

use craft\web\Controller;
use yii\web\Response;
use craft\web\View;

use craft\helpers\Db;

use imarc\databackup\elements\DataBackup as DataBackupElement;

class BackupController extends Controller
{
    protected array|bool|int $allowAnonymous = true;

    const DATA_TABLE = "{{%databackup_data}}";

    protected ?DataBackupElement $element;

    // protected array $params = [
    //     "source" => "string",
    //     "data" => "string"
    // ];

    public function beforeAction($action): bool
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    public function actionSave(): Response|string
    {

        $requestParams = $this->request->getBodyParams();
        $dataSource = $requestParams['source'] ?? null;
        $dataBody = $requestParams['data'] ?? null;
        $syke = $requestParams['syke'] ?? null;
        $response = new Response();

        $this->element = new DataBackupElement();

        if ($dataSource && $dataBody) {
            if ($syke) {
                $response->setStatusCode(202);
                return $response;
            }
            $response->setStatusCode(200);
            $response->content = json_encode([
                "dataSource" => $dataSource,
                "dataBody" => $dataBody
            ]);

            $this->element->data = $dataBody;
            $this->element->source = $dataSource;
            if (!Craft::$app->elements->saveElement($this->element)) {

                $response->setStatusCode(500);
                $response->content = "Problem saving element";
            } else {
                $response->setStatusCode(200);

            }
            return $response;
        } else {
            $response->setStatusCode(500);
            $response->content = "Missing post params";
            return $response;
        }

    }

    public function actionSearch(): Response|string
    {
        $requestParams = $this->request->getBodyParams();
        $search = $this->request->getParam("search") ?? null;

        $response = new Response();

        $likeDataCondition = new \yii\db\conditions\LikeCondition('{{%databackup_data}}.data', 'LIKE', $search);
        $likeSourceCondition = new \yii\db\conditions\LikeCondition('{{%databackup_data}}.source', 'LIKE', $search);

        $rows = (new \craft\db\Query())
            ->select(['id'])
            ->from('{{%databackup_data}}')
            ->where($likeDataCondition)
            ->orWhere($likeSourceCondition)
            ->all();

        $ids = json_encode(array_map(fn($a) => $a['id'], $rows));

        // $response->content = json_encode($ids);

        // return $response;
        // $ids = [];
        // foreach ($rows as $row) {
        //     $ids =
        // }

        return $this->renderTemplate(
            'data-backup/index',
            ['rows' => $ids],
            View::TEMPLATE_MODE_CP,
        );

    }

    public function actionEcho()
    {
        return $this->asJson(['ping' => 'Pong!']);
    }
}
