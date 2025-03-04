<?php

namespace imarc\databackup\controllers;

use Craft;

use craft\web\Controller;
use yii\web\Response;

use imarc\databackup\elements\DataBackup as DataBackupElement;

class BackupController extends Controller
{
    protected array|bool|int $allowAnonymous = true;

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

    public function actionEcho()
    {
        return $this->asJson(['ping' => 'Pong!']);
    }
}
