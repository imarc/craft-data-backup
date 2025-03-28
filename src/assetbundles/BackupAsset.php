<?php

namespace imarc\databackup\assetbundles;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use craft\web\assets\vue\VueAsset;

class BackupAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        $this->sourcePath = '@imarc/databackup/web/assets/dist';
        $this->depends = [
            CpAsset::class,
            VueAsset::class,
        ];

        $this->js = [
            'formatJSON.js',
        ];

        parent::init();
    }
}