<?php

namespace imarc\databackup;

use Craft;

use craft\base\Plugin;
use craft\base\Model;
use craft\web\twig\variables\Cp;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\web\UrlManager;
use craft\services\Elements;

use yii\base\Event;

use imarc\databackup\elements\DataBackup as DataBackupElement;

class DataBackup extends Plugin
{

    public string $schemaVersion = '1.0.0';
    public bool $hasCpSection = true;
    //public bool $hasCpSettings = true;

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
        });

        if (Craft::$app->request->isCpRequest) {
            $this->controllerNamespace = 'imarc\\databackup\\controllers';
        } elseif (Craft::$app->request->isConsoleRequest) {
            $this->controllerNamespace = 'imarc\\databackup\\controllers\\console';
        }
    }

    // protected function createSettingsModel(): ?Model
    // {
    //     return new Settings;
    // }

    private function attachEventHandlers(): void
    {
        // Event::on(
        //     Cp::class,
        //     Cp::EVENT_REGISTER_CP_NAV_ITEMS,
        //     function(RegisterCpNavItemsEvent $event) {
        //         $event->navItems[] = [
        //             'url' => 'data-backup',
        //             'label' => 'Data Backup'
        //         ];
        //     }
        // );

        Event::on(
            Elements::class,
            Elements::EVENT_REGISTER_ELEMENT_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = DataBackupElement::class;
            }
        );

        // Event::on(
        //     UrlManager::class,
        //     UrlManager::EVENT_REGISTER_CP_URL_RULES,
        //     static function (RegisterUrlRulesEvent $event) {
        //         $event->rules['ab-test/tests/new'] = 'ab-test/a-b-tests/edit';
        //         $event->rules['ab-test/tests/<testId:\d+>'] = 'ab-test/a-b-tests/edit';
        //     }
        // );

        // Event::on(
        //     CraftVariable::class,
        //     CraftVariable::EVENT_INIT,
        //     function (Event $event) {
        //         /** @var CraftVariable $variable */
        //         $variable = $event->sender;
        //         $variable->set('abtest', ABTestVariable::class);
        //     }
        // );

        // Event::on(
        //     TestElement::class,
        //     Element::EVENT_AFTER_DELETE,
        //     static function ($event) {
        //         $element = $event->sender;

        //         Db::delete('{{%abtest_tests}}', [
        //             'id' => $element->id,
        //         ]);

        //         Db::delete('{{%abtest_options}}', [
        //             'testId' => $element->id,
        //         ]);
        //     }
        // );
    }

}
