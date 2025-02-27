<?php

namespace imarc\databackup\elements;

use DateTime;

use Craft;
use craft\helpers\Db;
use craft\elements\db\ElementQuery;

class DataBackupQuery extends ElementQuery
{

    const DATA_TABLE = "{{%databackup_data}}";

    public ?array $data = null;
    public ?string $source = null;
    public mixed $dateCreated = null;

    public function data($value): self {
        $this->data = $value;
        return $this;
    }

    public function source($value): self {
        $this->source = $value;
        return $this;
    }

    protected function beforePrepare(): bool
    {

        Craft::info("data-backup", "Before prepare");
        $this->joinElementTable(self::DATA_TABLE);

        $this->query->select([
            '{{%databackup_data}}.source',
            '{{%databackup_data}}.data',
            '{{%databackup_data}}.dateCreated'
        ]);

        // if ($this->search) {
        //     Craft::info($this->search, 'data-backup');
        //     $likeCondition = new \yii\db\conditions\LikeCondition('{{%databackup_data}}.data', 'LIKE', '%' . $this->search . '%');
        //     $likeCondition->setEscapingReplacements(false);
        //     $this->subQuery->andWhere($likeCondition);
        // }

        if ($this->source) {
            $this->subQuery->andWhere(Db::parseParam('{{%databackup_data}}.source', $this->source));
        }

        if ($this->data) {
            $this->subQuery->andWhere(Db::parseParam('{{%databackup_data}}.data', $this->data));
        }

        return parent::beforePrepare();
    }

    protected function afterPrepare():bool
    {

        Craft::info(json_encode($this),"data-backup-after-prepare");
        return parent::afterPrepare();
    }

}
