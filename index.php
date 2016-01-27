<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CSV Editor</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/csveditor.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">CSV Editor</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
              <li class="active"><a href="index.php">Rediger CSV</a></li>
              <li><a href="help.php">Hjælp</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<?php

    require_once 'config.php';

// Get array of CSV files
$csvpath = SDFE_CSVFolder . "/";
$files = scandir($csvpath); // this is all files in dir

// clean up file list (to exclude)should only include csv files
$csvfiles = array();
foreach ($files as $basename) {
    if(substr($basename, -3)==SDFE_CSVFileExtension) {
        array_push($csvfiles, $basename);
    }
}
// Set first csv file as default csv file to display in edit mode
if(sizeof($csvfiles)>0) {
    $csv = $csvfiles[0];
}
// Override default csv file if a csv file is provided
if(isset($_GET["file"])) {
    $csv = $_GET["file"];
}

// Open CSV file
$filename = SDFE_CSVFolder . "/" . $csv;
$fp = fopen($filename, "r");
$content = fread($fp, filesize($filename));
$lines = explode("\n", $content);
fclose($fp);

?>      
      
      
      <div class="container-fluid">
        <div class="row">
<!-- List of CSV files -->
            <div class="col-lg-3 col-md-4 col-sm-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">CSV filer</h3>
                    </div>                
                    <div class="list-group">
<?php 
foreach ($csvfiles as $basename) {
    echo makeCSVFileLink($basename, $csv);
}
?>
                    </div>                
                </div>                
            </div>
<!-- Edit CSV content -->
            <div class="col-lg-9 col-md-8 col-sm-7">
           
<?php
if(!isset($csv)) {
    
}
else {
    // CSV file is not empty, let's show the content
    $row = explode(SDFE_CSVSeparator, $lines[0]);
    $columns = sizeof($row);
?>
                <form class="form-inline">
                <h1><?php echo $csv; ?></h1>
                <div class="panel panel-default">
                    <table class="table table-hover" id="csvtable">
                        <thead>
                            <tr>
<?php
    // Show header
    for ($columnCnt=0; $columnCnt<$columns; $columnCnt++) {
        echo "<th>" . $row[$columnCnt] . "</th>";
    }
    echo "<th>&nbsp;</th>";
?>
                            </tr>
                        </thead>                        
                        <tbody>
<?php
    // Show content
    for ($lineCnt=1; $lineCnt<sizeof($lines); $lineCnt++) {
        $row = explode(SDFE_CSVSeparator, $lines[$lineCnt]);
        echo makeTableRow($lineCnt, $row, $columns);
    }
?>
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <a href="#" id="addrow" class="btn btn-default"><i class="fa fa-plus"></i> Ny række</a>
                </div>
                <hr>
                <div>
                    <a href="#" id="cancel" class="btn btn-default"><i class="fa fa-undo"></i> Annulér</a>
                    <a href="#" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Gem ændringer</a>
                </div>
                </form>
                <div style="margin-top: 20px;" id="message">
                </div>

<?php
}
?>
            </div>
        </div>
    </div><!-- /.container -->
    <script src="js/jquery-1.11.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>

    var csvfile = "<?php echo $csv;?>";

    // Enable/disable row 
    $(document).on("click", "a[rel=editrow]", function(e) { 
//    $("a[rel=editrow]").click(function(e) { 
        e.preventDefault();
        // get id clicked a and extract the linenumber
        var linenum = this.id.split("-")[1];

        // change button icon and row background color according to state
        var rowIsEnabled;
        if ($(this).children().attr("class") === "fa fa-unlock-alt") {
            rowIsEnabled = true;
            $(this).children().attr("class", "fa fa-lock");
        }
        else {
            rowIsEnabled = false;
            $(this).children().attr("class", "fa fa-unlock-alt");
        }
        $("#row-"+ linenum).toggleClass("success");

        // Toggle (disable/enable) every input field in row
        $("input[rel=input-"+ linenum + "]").each(function( i ) {
            $(this).prop("disabled", rowIsEnabled);
        });
    });    
    
    // Delete row
    $(document).on("click", "a[rel=deleterow]", function(e) { 
//    $("a[rel=deleterow]").click(function(e) { 
        e.preventDefault();
        // get id clicked a and extract the linenumber
        var linenum = this.id.split("-")[1];
        // change background color of row to indicate that row is unlocked/locked
        $("#row-"+ linenum).hide();
    });

    // Add row
    $("#addrow").click(function(e) { 
        e.preventDefault();
        // get linenumber of last row
        var linenum = parseInt($("#csvtable tbody tr:last").attr("id").split("-")[1]);
        $("#csvtable tbody").append(makeTableRow(linenum+1, <?php echo $columns;?>, true));
    });
    
    // Cancel (reload page)
    $("#cancel").click(function(e) { 
        e.preventDefault();
        location.reload(true);
    });

    // Save
    $("#save").click(function(e) { 
        e.preventDefault();
        
        var csvlines = {};

        var columncnt = 0;
        var linecnt = 0;
        // Loop through all (visible only) table rows and make data
        $("[rel=row]:visible").each(function() {
            var linenum = this.id.split("-")[1];
            var thisline = {};
            columncnt = 0;
            $("input[rel=input-"+ linenum + "]").each(function() {
                thisline['col-'+columncnt] = $(this).val(); 
                columncnt++;
            });
            csvlines['line-'+linecnt] = thisline;
            linecnt++;
        });
        var csvdata = {csvfile: csvfile, lines: linecnt, columns: columncnt, data: csvlines};
        //alert(JSON.stringify(csvdata));

        // Write data to file and show result to user
        $.ajax({
            url: "savetocsv.php",
            method: "POST",
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            async: false,
            data: JSON.stringify(csvdata),
            cache: false,
            success: function(response) {
                makeMessage("<h4>" + response.responseText + "</h4>Siden vil blive genindlæst om et øjeblik!", "success", "message");
                // reload page in 3 sec
                setTimeout(function(){location.reload();}, 2500);
                
            },
            error: function(response) {
                makeMessage("<h4>Ups</h4>" + response.status + " " + response.statusText, "danger", "message");
            }
        });        
    });


    function makeMessage(messagetext, messagetype, messageid){
        var h = "<div class=\"alert alert-" + messagetype + " alert-dismissible\" role=\"alert\">"
            + "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>"
            + messagetext + "</div>";
        $("#"+messageid).html(h);
        return;
    }

    function makeTableRow(linenum, columns, isenabled) {
        var h = "<tr rel=\"row\" id=\"row-" + linenum + "\" class=\"" + (isenabled===true ? "success" : "") + "\">";
        for (var columncnt=0; columncnt<columns; columncnt++) {
            h += "<td><input class=\"form-control" + (columncnt==0 ? " input-col-first" : " input-col-rest") + "\" rel=\"input-" + linenum + "\"" + (isenabled===true ? "" : " disabled") + " type=\"text\" value=\"\"></td>";
        }
        h += "<td>";
        h += " <a href=\"#\" rel=\"editrow\" id=\"editrow-" + linenum + "\" title=\"Edit row\" class=\"btn btn-default btn-sm\"><i class=\"fa " + (isenabled===true ? "fa-unlock-alt" : "fa-lock") + "\"></i></a>";
        h += " <a href=\"#\" rel=\"deleterow\" id=\"deleterow-" + linenum + "\" title=\"Delete row\" class=\"btn btn-default btn-sm\"><i class=\"fa fa-trash\"></i></a>";
        h += "</td>";
        h += "</tr>";
        return h;
    }
 </script>
  </body>
</html>
<?php
function makeTableRow($lineCnt, $row, $columns) {
    $h = "<tr rel=\"row\" id=\"row-" . $lineCnt . "\">";
    for ($columnCnt=0; $columnCnt<$columns; $columnCnt++) {
        $h .= "<td><input class=\"form-control" . ($columnCnt==0 ? " input-col-first" : " input-col-rest") . "\" rel=\"input-" . $lineCnt . "\" disabled type=\"text\" value=\"" . $row[$columnCnt] . "\"></td>";
    }
    $h .= "<td>";
    $h .= " <a href=\"#\" rel=\"editrow\" id=\"editrow-" . $lineCnt . "\" title=\"Edit row\" class=\"btn btn-default btn-sm\"><i class=\"fa fa-lock\"></i></a>";
    $h .= " <a href=\"#\" rel=\"deleterow\" id=\"deleterow-" . $lineCnt . "\" title=\"Delete row\" class=\"btn btn-default btn-sm\"><i class=\"fa fa-trash\"></i></a>";
    $h .= "</td>";
    $h .= "</tr>";
    
    return $h;
}
function makeCSVFileLink($basename, $activebasename) {
    // Include CSV files only (defined by extension)
    if(substr($basename, -3)==SDFE_CSVFileExtension) {
        $h = "<a href=\"?file=" . $basename . "\" ";
        $h .= "class=\"list-group-item" . ($basename==$activebasename ? " active" : "") . "\">";
        $h .= $basename . "</a>";
    }
    return $h;
}

?>
