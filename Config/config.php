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
            ],
        ],
    ],
    'routes' => [
        'main' => [
            'plugin_icc_customimport_importvbcode' => [
                'path'       => '/icccustomimport/importfilevbcode',
                'controller' => ImportFileVbCodeController::class . '::importFileVBCodeAction',
                'method'     => 'POST',
            ],
            'plugin_icc_customimport_importkundenumfrage' => [
                'path'       => '/icccustomimport/importfilekundenumfrage',
                'controller' => ImportFileKundenumfrageController::class . '::importFileKundenumfrageAction',
                'method'     => 'POST',
            ],
        ],
    ],
    'menu'       => [],
    'parameters' => [],
];
