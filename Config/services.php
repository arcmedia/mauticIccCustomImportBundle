<?php

declare(strict_types=1);

//use Mautic\CoreBundle\Configurator\Configurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('MauticPlugin\\IccCustomImportBundle\\', '../')
        ->exclude('../{Config,Integration,IccCustomImportBundle.php}');

    // Special handling for controllers that need the tmp_path parameter
    $services->load('MauticPlugin\\IccCustomImportBundle\\Controller\\', '../Controller')
        ->arg('$uploadDir', '%mautic.tmp_path%')
        ->public();
};
