<?php

declare(strict_types=1);

namespace MauticPlugin\IccCustomImportBundle;

use Mautic\IntegrationsBundle\Bundle\AbstractPluginBundle;
use Mautic\PluginBundle\Entity\Plugin;
use Mautic\CoreBundle\Factory\MauticFactory;

class IccCustomImportBundle extends AbstractPluginBundle
{
    public static function onPluginUpdate(Plugin $plugin, MauticFactory $factory, $metadata = null, ?\Doctrine\DBAL\Schema\Schema $installedSchema = null): void
    {
        if ($metadata === null) {
            $metadata = [];
        }

        parent::onPluginUpdate($plugin, $factory, $metadata, $installedSchema);
    }
}
