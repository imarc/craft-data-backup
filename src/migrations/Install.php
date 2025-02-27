<?php

namespace imarc\databackup\migrations;

use Craft;
use craft\db\Migration;

class Install extends Migration
{

    const DATA_TABLE = "{{%databackup_data}}";

    public string $driver;

    public function safeUp(): bool
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->createTables();

        return true;
    }

    public function safeDown(): bool
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    protected function createTables(): bool
    {
        $tableCreated = false;
        $tableSchema = Craft::$app->db->schema->getTableSchema(self::DATA_TABLE);
        if ($tableSchema === null) {
            $this->createTable(
                self::DATA_TABLE,
                [
                    'id' => $this->primaryKey(),
                    'source' => $this->string(),
                    'data' => $this->text(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                ]
            );
            $tableCreated = true;
        }

        $this->addForeignKey(
            null,
            self::DATA_TABLE,
            'id',
            '{{%elements}}',
            'id',
            'CASCADE',
            null
        );

        $this->createIndex(null, self::DATA_TABLE, ['source'], false);
        $this->createIndex(null, self::DATA_TABLE, ['data'], false);

        return $tableCreated;
    }

    protected function removeTables()
    {
        $this->dropTableIfExists(self::DATA_TABLE);
    }
}
