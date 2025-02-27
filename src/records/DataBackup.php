<?php

namespace imarc\databackup\records;

use craft\db\ActiveRecord;
use craft\db\ActiveQuery;

class DataBackup extends ActiveRecord
{

    public static function tableName() {
        return "{{%databackup_data}}";
    }

    public function getDataBackup(): array
    {
        return [];
    }
}
