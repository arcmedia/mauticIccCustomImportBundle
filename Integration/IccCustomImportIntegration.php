<?php

namespace MauticPlugin\IccCustomImportBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class IccCustomImportIntegration extends AbstractIntegration
{
    public function getName(): string
    {
        return 'IccCustomImport';
    }
    
    public function getAuthenticationType(): string
    {
        return 'none';
    }
    
    public function getRequiredKeyFields(): array
    {
        return [];
    }
    
    /**
     * {@inheritdoc}
     *
     * @param $section
     *
     * @return string|array
     */
    public function getFormNotes($section)
    {
        if ('leadfield_match' == $section) {
            return ['mautic.integration.form.field_match_notes', 'info'];
        }
        if ($section === "custom") {
            return [
                'template'   => 'IccCustomImportBundle:Integration:form.html.php',
                'parameters' => [],
            ];
        }
        return ['', 'info'];
    }

    public function getDescription()
    {
        return null;
    }

    public function getSupportedFeatures(): array
    {
        return [];
    }

    public function getFormSettings(): array
    {
        return [
            'requires_callback'      => false,
            'requires_authorization' => false,
        ];
    }

    public function getDisplayName(): string
    {
        return 'Custom Import';
    }

    public function getIcon(): string
    {
        return 'plugins/IccCustomImportBundle/Assets/img/logo.png'; 
    }

    public function getDataPriority(): bool
    {
        return false;
    }

    public function appendToForm(&$builder, $data, $formArea): void
    {
        if ('features' == $formArea) {
           
            $builder->add(
                'import_file_vba_code',
                FileType::class,
                [
                    'attr' => [
                        'accept'   => '.csv',
                        'class'    => 'form-control',
                        'required' => false,
                    ],
                    'mapped' => false
                ],
            );

            $builder->add(
                'start_vbacode',
                ButtonType::class,
                [
                    'attr'  => [
                        'class'   => 'btn btn-primary',
                        //'icon'    => 'fa',
                        'onclick' => 'Mautic.uploadVBACSV(this);',
                    ],
                    'label' => 'Start',
                ]
            );

            $builder->add(
                'import_file_kundenumfrage',
                FileType::class,
                [
                    'attr' => [
                        'accept'   => '.csv',
                        'class'    => 'form-control',
                        'required' => false,
                    ],
                    'mapped' => false
                ]
            );

            $builder->add(
                'start_kundenumfrage',
                ButtonType::class,
                [
                    'attr'  => [
                        'class'   => 'btn btn-primary',
                        //'icon'    => 'fa',
                        'onclick' => 'Mautic.uploadKundenumfrageCSV(this);',
                    ],
                    'label' => 'Start',
                ]
            );
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'theme_upload';
    }
}
