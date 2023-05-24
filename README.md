# Silverstripe file cloner

A quick silverstripe build task to generate lots of files

Only use this for testing!

## Instalation:

`composer require emteknetnz/silverstripe-file-cloner`

## Usage:

Login as admin

`dev/tasks/FileClonerTask?fileID=123&folderID=456&num=1000`

### Querystring parameters
- fileID - the file ID of the file you wish to clone
- folderID - the folder ID of the folder you wish to clone all the files into
- num - the number of files to clone - between 1 and 10,000
