<?php

namespace MauticPlugin\IccCustomImportBundle\Controller;

use DateTime;
use Mautic\CoreBundle\Controller\AjaxController;
use Mautic\LeadBundle\Entity\Import;
use Mautic\LeadBundle\Entity\LeadList;
use Mautic\LeadBundle\Model\ImportModel;
use Mautic\LeadBundle\Model\ListModel;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImportFileKundenumfrageController extends AjaxController
{
    private string $originalFilename = "";
    private string $fileName = "";
    private string $path = "";
    private array $fileHeader = [];
    
    public function __construct(
        private LoggerInterface $logger,
        private ImportModel $importModel,
        private ListModel $listModel,
        private string $uploadDir,
    )
    {}

    public function importFileKundenumfrageAction(Request $request): JsonResponse
    {
        ini_set('upload_max_filesize', '7000000'); //7 mb
        ini_set('post_max_size', '7000000'); //7 mb
        ini_set('max_file_uploads', '100');

        $listName = 'Kundenumfrage ' . date('F, Y');
        
        $listId = $this->getLeadId($listName);

        try {
            $filePath = $this->moveUploadedFile($request);
        } catch (FileException $e) {
            return $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }

        $lineCount = $this->countLinesOfFile($filePath);
        
        $this->saveImportEntity($listId, $lineCount);

        return $this->sendJsonResponse([]);
    }
    
    private function saveImportEntity(int $listId, int $lineCount) 
    {
        $properties = $this->getProperties($listId);
        $importEntity = new Import();
        $importEntity->setOriginalFile($this->originalFilename);
        $importEntity->setLineCount($lineCount);
        $importEntity->setDir($this->path);
        $importEntity->setFile($this->fileName);
        $importEntity->setPriority(512);
        $importEntity->setStatus(1);
        $importEntity->setObject('lead');
        $importEntity->setProperties($properties);

        $this->importModel->saveEntity($importEntity);
    }
    
    private function getProperties(int $listId): array
    {
        return [
            "fields" => [
                "ansprache" => "ansprache",
                "beleg" => "belegenr",
                "belegdatum" => "belegdatum",
                "email" => "email",
                "firma" => "companyname",
                "inhaber" => "ownerusername",
                "name" => "lastname",
                "sprachcode" => "preferred_locale",
                "vb_code" => "vb_code",
                "erster_umsatz" => "ersterumsatz",
                "ref" => "emailingref"
            ],
            "defaults" => [
                "owner" => null,
                "list" => $listId,
                "tags" => [
                ],
                "skip_if_exists" => false
            ],
            "headers" => $this->fileHeader,
            "parser" => [
                "delimiter" => ";",
                "enclosure" => "\"",
                "escape" => "\\",
                "batchlimit" => 100
            ]
        ];
    }
    
    private function countLinesOfFile(string $filePath): int
    {
        $c = 0;
        $fp = fopen($filePath, 'r');

        if (!$fp) {
            return 0;
        }
        
        $this->fileHeader = fgetcsv($fp,1000,";");
        while (!feof($fp)) {
            $content = fgets($fp);
            if ($content) {
                ++$c;
            }
        }
        fclose($fp);
        return $c;
    }
    
    private function moveUploadedFile(Request $request): string
    {
        /* @var UploadedFile */
        $file = $request->files->get('file');
        $this->path = $this->uploadDir . '/imports';
        if (!is_dir($this->path)) {
            mkdir($this->path, 0775, true);
        }

        $currentTime = date('YmdHis');
        $this->originalFilename = $file->getClientOriginalName();
        $this->fileName = $currentTime . '.csv';
        $filePath = $this->path . '/' . $this->fileName;

        try {
            $file->move($this->path, $this->fileName);
        } catch (FileException $e) {
            throw new FileException($e->getMessage());
        }
        return $filePath;
    }
    
    private function getLeadId(string $listName): int
    {
        $listName = 'Kundenumfrage ' . date('F, Y');
        $alias = $this->listModel->cleanAlias($listName, '', false, '-');
        $entity = $this->listModel->getRepository()->findOneBy(
            [
                'alias' => $alias,
            ]
        );

        if ($entity === null) {
            $entity = $this->createNewLead($listName);
        }
        
        return (int) $entity->getId();
    }
    
    private function createNewLead(string $listName) 
    {
        $leadListEntity = new LeadList();
        $leadListEntity->setName($listName);
        $leadListEntity->setPublicName($listName);
        $alias = $this->listModel->cleanAlias($listName, '', false, '-'); 
        $leadListEntity->setAlias($alias);
        $dateAdded = new DateTime();
        $leadListEntity->setDateAdded($dateAdded);

        $this->listModel->getRepository()->saveEntity($leadListEntity);
        $entity = $this->listModel->getRepository()->findOneBy(
            [
                'alias' => $alias,
            ]
        );
        return $entity;
    }
}
