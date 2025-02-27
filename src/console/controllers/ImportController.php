<?php

namespace imarc\databackup\console\controllers;

use Craft;
use yii\console\Controller;

class ImportController extends Controller
{
    // run with command php craft data-backup/import

    public function actionIndex() {

        echo "Running import\n";

        return "Action Index";

    }


}
