<?php

declare(strict_types=1);

namespace MauticPlugin\IccCustomImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
class CustomImportMetadata
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected ?int $id = null;
}