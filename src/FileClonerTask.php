<?php

use SilverStripe\Dev\BuildTask;
use SilverStripe\Security\Permission;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Folder;

class FileClonerTask extends BuildTask
{
    protected $title = 'File Cloner Task';
    protected $description = 'Clones an existing file into an existing folder a large number of times';

    public function run($request)
    {
        if (!Permission::checkMember(null, 'ADMIN')) {
            echo 'You must be logged in as an admin to run this task';
            return;
        }

        $folderID = $request->getVar('folderID');
        $fileID = $request->getVar('fileID');
        $num = $request->getVar('num');

        if (!$folderID || !$fileID || !$num) {
            echo 'Please provide query string values for folderID, fileID and num';
            return;
        }

        $file = File::get()->byID($fileID);
        if (!$file) {
            echo 'File not found';
            return;
        }

        $folder = Folder::get()->byID($folderID);
        if (!$folder) {
            echo 'Folder not found';
            return;
        }

        if ($num > 10000) {
            echo 'Please provide a num between 1 and 10,000';
            return;
        }

        $fileID = $file->ID;
        $t = time();
        for ($i = 1; $i <= $num; $i++) {
            $filename = "$fileID-$t-$i";
            // copy the physical-file
            $targetPath = dirname($folder->getFileName()) . '/' . $filename;
            echo $targetPath;
            // $file->copyFile($targetPath);
            // create the file DataObject
        }
        echo "Create $num files in $folder->Name";
    }
}