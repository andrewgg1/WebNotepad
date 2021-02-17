<?php

//  	FILE				: server.php
//     	PROJECT				: Web Design and Development PROG2001 - Assignment 7
//     	PROGRAMMER			: Andrew Gordon
//     	FIRST VERSION		: Dec. 8 2020
//     	DESCRIPTION			: Provides all server background functionality. Includes, loading files,
//                            writing new/overwriting files, loading the list of files
//     


// save the directory
$fileDir = './MyFiles/';
// decode the client GET request
$selection = json_decode($_GET['args'], true)['userSelection'];

// client is requesting a list of all files in the directory
if($selection === 'allFiles')
{
    // save the full file list to an array
    $fileList = [];
    foreach (new DirectoryIterator($fileDir) as $file) {
        if ($file->isFile()) {
            array_push($fileList, $file->getFilename());
        }
    }
    // return the encoded array
    echo json_encode($fileList);  
}
// client wants to overwrite file
elseif ($selection === 'Overwrite File')
{
    // get the filename and contents of the file
    $filename = json_decode($_GET['args'], true)['filename'];
    $contents = json_decode($_GET['args'], true)['textfile'];
    // build the file location and filename
    $fileLoc = $fileDir . $filename;
    // open file
    $txtFile = fopen($fileLoc, "w");
    if($txtFile != FALSE)
    {
        // if opening was successful, write to the file and return a status message
        fwrite($txtFile, $contents);
        fclose($txtFile);      
        echo "File Overwritten: " . ' ' . $filename;
    }
    else
    {
        // if unsuccessful in opening, return an error message
        echo "File couldn't be overwritten";
    }
}
// client wants to save to a new file
elseif ($selection === 'Save file')
{
    // get the filename and contents of the file
    $filename = json_decode($_GET['args'], true)['filename'];
    $contents = json_decode($_GET['args'], true)['textfile'];
    // build the file location and filename
    $fileLoc = $fileDir . $filename;
    // create the new file
    $txtFile = fopen($fileLoc, "w");
    if($txtFile != FALSE)
    {
        // if creating the file was successful, write to the file and return a status message
        fwrite($txtFile, $contents);
        fclose($txtFile);      
        echo "File Saved as " . ' ' . $filename;
    }
    else
    {
        // if unsuccessful, return an error message
        echo "File couldn't be saved";
    }
}
// client wants to open a file
else
{
    // get the filename and build the location
    $filename = json_decode($_GET['args'], true)['userSelection'];
    $fileLoc = $fileDir . $filename;
    // open file
    $txtFile = fopen($fileLoc, "r");
    if($txtFile != FALSE)
    {
        // if successfully opened, read from file until end of file
        while(!feof($txtFile))
        {
            // save each line
            $strOutput .= fgets($txtFile);
        }
        fclose($txtFile);        
    }
    else
    {
        // if unsuccessful, return an error message
        echo "File couldn't be opened";
    }
    // return the encoded contents of the file
    echo json_encode($strOutput);
}

?>
