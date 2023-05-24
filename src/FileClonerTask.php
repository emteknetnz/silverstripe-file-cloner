<?php

namespace emteknetnz\SilverstripeFileCloner;

use SilverStripe\Dev\BuildTask;
use SilverStripe\Security\Permission;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Folder;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Assets\Storage\AssetStore;
use SilverStripe\Core\Environment;

class FileClonerTask extends BuildTask
{
    protected $title = 'File Cloner Task';

    protected $description = 'Clones an existing file into an existing folder a large number of times';

    private static $segment = 'FileClonerTask';

    public function run($request)
    {
        if (!Permission::checkMember(null, 'ADMIN')) {
            $this->log('You must be logged in as an admin to run this task');
            return;
        }

        // remove time limit
        Environment::increaseTimeLimitTo(null);

        $folderID = $request->getVar('folderID');
        $fileID = $request->getVar('fileID');
        $num = $request->getVar('num');

        if (!$folderID || !$fileID || !$num) {
            $this->log('Please provide query string values for folderID, fileID and num');
            return;
        }

        $file = File::get()->byID($fileID);
        if (!$file) {
            $this->log('File not found');
            return;
        }

        if (!$file->isPublished()) {
            $this->log('File must be published so that it is in the public asset store');
            return;
        }

        $folder = Folder::get()->byID($folderID);
        if (!$folder) {
            $this->log('Folder not found');
            return;
        }

        if ($num > 10000) {
            $this->log('Please provide a num between 1 and 10,000');
            return;
        }

        $fileID = $file->ID;
        $t = time();
        $store = Injector::inst()->get(AssetStore::class);
        $filesystem = $store->getPublicFilesystem();
        $stream = $filesystem->readStream($file->Filename);

        for ($i = 1; $i <= $num; $i++) {
            $newName = $folder->Filename . "$fileID-$t-$i." . $file->getExtension();
            // will create in the protected filesystem
            $newFile = File::create();
            $newFile->setFromStream($stream, $newName);
            $newFile->write();
            // publish it to ensure that it is in the public filesystem so that if there's
            // a mix of files they're not in different hash directories
            $newFile->publishRecursive();
        }
        $this->log("Success! Created $num files in the folder $folder->Name");
    }

    private function log($s)
    {
        echo "$s\n";
    }
}