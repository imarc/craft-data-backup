<?php

namespace imarc\databackup\elements;

use Craft;
use craft\base\Element;
use craft\elements\User;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\Db;

use imarc\databackup\elements\DataBackupQuery;
use imarc\databackup\records\DataBackup as DataRecord;

class DataBackup extends Element
{
    const DATA_TABLE = "{{%databackup_data}}";

    public mixed $data = null;
    public ?string $source = null;

    // public function rules(): array
    // {
    //     return [
    //         [['data'],
    //             'targetClass' => DataRecord::class,
    //         ],
    //         [['source'],
    //             'targetClass' => DataRecord::class,
    //         ],
    //     ];
    // }

    public function isNew(): bool
    {
        return !$this->id;
    }

    public static function displayName(): string
    {
        return Craft::t('data-backup', 'Data Backup');
    }

    public static function pluralDisplayName(): string
    {
        return Craft::t('data-backup', 'Data Backups');
    }

    public static function refHandle(): ?string
    {
        return 'data-backup';
    }

    public static function find(): ElementQueryInterface
    {
        return new DataBackupQuery(static::class);
    }

    public function afterSave(bool $isNew = true): void
    {

        Craft::info(json_encode($this->data), "data-backup");
        if (!$this->propagating) {
            Db::upsert(self::DATA_TABLE, [
                'id' => $this->id,
                'data' => json_encode($this->data, JSON_PRETTY_PRINT),
                'source' => $this->source,
            ]);
        }


        parent::afterSave($isNew);

    }

    public static function defineDefaultTableAttributes(string $source): array
    {
        return ['source', 'data', 'dateCreated'];
    }

    protected static function defineTableAttributes(): array
    {
        return [
            'source' => Craft::t('app', 'Source'),
            'data' => Craft::t('app', 'Data'),
            'dateCreated' => Craft::t('app', 'Date Created'),
        ];
    }

    protected static function defineSearchableAttributes(): array
    {
        return ['source', 'data'];
    }


    protected static function defineSources(string $context = null): array
    {
        return [
            [
                'key' => '*',
                'label' => 'All Data',
                'criteria' => []
            ],
        ];
    }

    public function getUiLabel(): string
    {
        return strval($this->id);
    }

    public function canView(User $user): bool
    {
        return true;
    }

    public function canDelete(User $user): bool
    {
        return true;
    }

}
