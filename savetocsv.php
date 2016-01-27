<?php
    require_once 'config.php';

    // message
    $message = array("responseText" => "Ændringer gemt!");

    // Get posted object
    $json = json_decode(file_get_contents('php://input'), true);
    
    // Backup file by moving it to csvbackup folder. If file exists it will be overwritten!
    $csvfile = $json["csvfile"];
    $csvfileorg = SDFE_CSVFolder . "/" . $csvfile;
    $csvfilebackup = SDFE_CSVFolderBackup . "/" . $csvfile;
    copy($csvfileorg, $csvfilebackup);
    
    // Read first line (header) from current CSV file
    $newcsvfile = fopen($csvfileorg, "r") or die("Unable to open file!");
    $header = fgets($newcsvfile);
    fclose($newcsvfile);
    
    // Delete existing data in current CSV file and Write new content
    $newcsvfile = fopen($csvfileorg, "w") or die("Unable to open file!");

    // write header
    fwrite($newcsvfile, $header);

    $columns = $json["columns"];
    $lines = $json["lines"];
    
    // for every line array lopp through and write a line to the csv
    for($lineCnt=0; $lineCnt<$lines; $lineCnt++) {
        $lineContent = ""; // reset line content
        $lineObj = $json["data"]["line-".$lineCnt];
        for($columnCnt=0; $columnCnt<$columns; $columnCnt++) {
            if($columnCnt>0) {
                $lineContent .= SDFE_CSVSeparator;
            }
            $lineContent .= $lineObj["col-".$columnCnt]; 
        }

        // add new line char to the end of line
        if($lineCnt<$lines-1) {
            $lineContent .= SDFE_CSVLineTerminator;
        }
        // write line content
        fwrite($newcsvfile, $lineContent);
    }
    fclose($newcsvfile);

    // Send message to user
    echo json_encode($message);
    return;
?>