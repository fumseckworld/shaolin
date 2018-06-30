# File management system

# Methods static

| Name                  | Do                                        | Arguments                         | Return        |
|-----------------------|-------------------------------------------|-----------------------------------|---------------|    
| hash                  | Get the MD5 hash of the file              | string  $filename                 | string        |
| lastModified          | Get the file's last modification time     | string  $filename                 | bool|int      |
| download              | download a file                           | string  $filename                 | bool|int      |
| search                | search files likes a pattern              | string  $pattern int $flag        | array         |
| create                | create a file is not exist                | string  filename                  | bool          |
| delete                | delete a file or a folder if exist        | string  filename                  | bool          |
| deleteFolder          | delete a folder if exist                  | string  $folder                   | bool          |
| getLines              | get all lines in a file                   | string  $filename, string $mode   | array         |
| getKeys               | get all keys in a file                    | string  multiples                 | array         |
| getValues             | get all values in a file                  | string  multiples                 | array         |
| copyFolder            | copy a folder to destination              | string  multiples                 | void          |
| copy                  | copy a source to destination              | string  multiples                 | bool          |
| isReadable            | check if a file or a folder is readable   | string  $filename                 | bool          |
| isWritable            | check if a file or a folder is writable   | string  $filename                 | bool          |
| hardLink              | create a hard link                        | string  multiples                 | bool          |
| symlink               | create a symlink link                     |  string  multiples                | bool          |
| isLink                | check if filename is a symlink            | string  $filename                 | bool          |
| getMime               | get the mime of file                      | string  $filename                 | string        |
| getStat               | get the file's info                       | string  $filename                 | array         |
| getStartKey           | get a part of stat by a key               | string  multiples                 | string        |
| write                 | write data on a file                      | string  multiples                 | bool          |
| isFile                | check if filename is a file               | string  $filename                 | bool          |
| isImg                 | check if filename is an image             | string  $filename                 | bool          |
| isHtml                | check if filename is a html file          | string  $filename                 | bool          |
| isPhp                 | check if filename is a php file           |  string  $filename                | bool          |
| isJS                  | check if filename is a js file            |  string  $filename                | bool          |
| isJson                | check if filename is a json file          |  string  $filename                | bool          |
| isXml                 | check if filename is a xml file           |  string  $filename                | bool          |
| isCss                 | check if filename is a css file           |  string  $filename                | bool          |
| isPdf                 | check if filename is a pdf file           |  string  $filename                | bool          |
| isEnd                 | check if is the end of file               |  string  $filename                | bool          |
| getGroup              | get the group of the file                 |  string  $filename                | int           |
| getOwner              | get the owner of the file                 |  string  $filename                | int           |
| loads                 |include all file passed in parameters      |  string multiples                 | void          |
| getContent            | get content in a file                     |  string $filename                 | string        |
| putContents           | write data on a file                      |  string $filename                 | bool          |
| getFile               | get super global $_FILES                  |  void                             | $_FILES       |
| uploadedFileType      | get uploaded file type                    |  string $inputName                | string        |
| uploadedFileSize      | get uploaded file sie                     |  string $inputName                | int           |
| uploadedFileName      | get uploaded file name                    |  string $inputName                | string        |
| uploadedFileTmpPath   | get uploaded file tmp path                |  string $inputName                | string        |
| uploadedFileErrors    | get uploaded file tmp error               |  string $inputName                | int           |
| moveUploadedFile      | move a uploaded file to destination       |  string multiples                 | bool          |
| rename                | rename a file                             |  string multiples                 | bool          |
| verify                | verify a file exist and if is a file      |  string $filename                 | bool          |
| exist                 | verify a file exist                       |  string $filename                 | bool          |
| open                  | open a file with a mode                   |  string multiple                  | resource      |
| isEmptyArgs           | check if function argument is empty       |  void                             | bool          |
| close                 | close a file                              |  resource $file                   | bool          |
| realPath              | get absolute path                         |  resource $file                   | string        |
| chmod                 | Changes file mod                          |  string multiples                 | bool          |
| chgrp                 | Changes file group                        |  string multiples                 | bool          |
| lchgrp                | Changes group ownership of symlink        |  string multiples                 | bool          |
| chown                 | Changes file owner                        |  string multiples                 | bool          |
| isExecutable          | Tells whether the filename is executable  |  string $filename                 | bool          |
| getType               | gets file type                            |  string $filename                 | string        |
| fileTime              | gets last access time of file             |  string $filename                 | int           |
| fileOwner             | gets file owner                           |  string $filename                 | int           |