# Silverstripe file cloner

A quick silverstripe build task to generate lots of published files in a single folder

Only use this for testing!

**Important!** if you copy images you'll end up with 4x the number of files that you expect because variants will be created, so recommend you just use PDF files

## Instalation:

`composer require emteknetnz/silverstripe-file-cloner`

## Usage:

1. Login as admin
2. Ensure there are 2x folders:
- First folder contain one or more source files that will be cloned - recommend you use PDFs and not images
- Second folder is where you will clone the files to
3. Ensure the file(s) in the first folder have been published via the CMS
4. Run the build task `https://mysite.test/dev/tasks/FileClonerTask?fileID=123&folderID=456&num=1000`

### Querystring parameters
- fileID - the file ID of the file you wish to clone from the first folder
- folderID - the folder ID of the second folder you wish to clone all the files into
- num - the number of files to clone - between 1 and 10,000
