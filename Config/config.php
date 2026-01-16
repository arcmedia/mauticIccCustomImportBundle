<?php

use MauticPlugin\IccCustomImportBundle\Controller\ImportFileKundenumfrageController;
use MauticPlugin\IccCustomImportBundle\Controller\ImportFileVbCodeController;
use MauticPlugin\IccCustomImportBundle\Integration\IccCustomImportIntegration;

return [
    'name'        => 'Custom Import Bundle',
    'description' => 'Custom Import',
    'author'      => 'Arcmedia',
    'version'     => '2.0.0',
    'services'    => [
        'controllers' => [
            ImportFileVbCodeController::class => [
                'class'     => ImportFileVbCodeController::class,
                'arguments' => [
                    'monolog.logger.mautic',
                    'mautic.lead.model.import',
                    '%mautic.tmp_path%',
                ],
            ],
            ImportFileKundenumfrageController::class => [
                'class'     => ImportFileKundenumfrageController::class,
                'arguments' => [
                    'monolog.logger.mautic',
                    'mautic.lead.model.import',
                    'mautic.lead.model.list',
                    '%mautic.tmp_path%',
                ],
            ],
        ],
        'integrations' => [
            'mautic.integration.icccustomimport' => [
                'class'     => IccCustomImportIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                    'mautic.lead.field.fields_with_unique_identifier',
                ],
                //'tags'=> ['mautic.integration'],
            ],
        ],
    ],
    'routes' => [
        'main' => [
            'plugin_icc_customimport_importvbcode' => [
                'path'       => '/icccustomimport/importfilevbcode',
                'controller' => 'MauticPlugin\IccCustomImportBundle\Controller\ImportFileVbCodeController::importFileVBCodeAction',
                'method'     => 'POST',
            ],
            'plugin_icc_customimport_importkundenumfrage' => [
                'path'       => '/icccustomimport/importfilekundenumfrage',
                'controller' => 'MauticPlugin\IccCustomImportBundle\Controller\ImportFileKundenumfrageController::importFileKundenumfrageAction',
                'method'     => 'POST',
            ],
        ],
    ],
    'menu'       => [],
    'parameters' => [],
];
