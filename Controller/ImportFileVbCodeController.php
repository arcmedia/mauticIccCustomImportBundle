<?php

namespace MauticPlugin\IccCustomImportBundle\Controller;

use Mautic\CoreBundle\Controller\AjaxController;
use Mautic\LeadBundle\Entity\Import;
use Mautic\LeadBundle\Model\ImportModel;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

class ImportFileVbCodeController extends AjaxController
{
    private string $originalFilename = "";
    private string $fileName = "";
    private string $path = "";

    public function __construct(
        private LoggerInterface $logger,
        private ImportModel $importModel,
        private string $uploadDir,
    )
    {}

    public function importFileVBCodeAction(Request $request)
    {
        ini_set('upload_max_filesize', '7000000'); //7 mb
        ini_set('post_max_size', '7000000'); //7 mb
        ini_set('max_file_uploads', '100');
        
        try {
            $filePath = $this->moveUploadedFile($request);
        } catch (FileException $e) {
            return $this->sendJsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
        
        $lineCount = $this->countLinesOfFile($filePath);

        $this->saveImportToDb($lineCount);
        return $this->sendJsonResponse([]);
    }
    
    private function saveImportToDb(int $lineCount) 
    {
        $properties = $this->getCsvSettings();
        
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
    
    private function getCsvSettings(): array 
    {
        return [
            "fields" => [
                "email" => "email",
                "inhaber" => "ownerusername",
                "vb_code" => "vb_code"
            ],
            "parser" => [
                "escape" => "\\",
                "delimiter" => ";",
                "enclosure" => "\"",
                "batchlimit" => 100
            ],
            "headers" => [
                "﻿email",
                "vb_code",
                "inhaber"
            ],
            "defaults" => [
                "list" => null,
                "tags" => [
                ],
                "owner" => null,
                "skip_if_exists" => false
            ]
        ];
    }
    
    /**
     * Puts the uploaded file into var/tmp/imports
     * @param Request $request
     * @return string               New path to access file
     */
    private function moveUploadedFile(Request &$request): string
    {
        /* @var UploadedFile */
        $file = $request->files->get('file');
        $this->path = $this->uploadDir . '/imports';
        
        if (!is_dir($this->path)) {
            mkdir($this->path, 0775, true);
        }

        $currentTime = date('YmdHis');
        $this->fileName = $currentTime . '.csv';
        $filePath = $this->path . '/' . $this->fileName;
        $this->originalFilename = $file->getClientOriginalName();
        
        try {
            $file->move($this->path, $this->fileName);
        } catch (FileException $e) {
            throw new FileException($e->getMessage());
        }
        return $filePath;
    }
    
    /**
     * Counts the lines of a file
     * @param string $filePath
     * @return int
     */
    private function countLinesOfFile(string $filePath): int 
    {
        $c = 0;
        $fp = fopen($filePath, 'r');
        if ($fp) {
            while (!feof($fp)) {
                $content = fgets($fp);
                if ($content) {
                    ++$c;
                }
            }
        }
        fclose($fp);
        return $c;
    }
}
