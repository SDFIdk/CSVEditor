<?php
    require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CSV Editor Help</title>

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
              <li><a href="index.php">Rediger CSV</a></li>
              <li class="active"><a href="help.php">Hjælp</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1>Hjælp</h1>
                <h2>Forudsætninger</h2>
                <p>
                    Første række i en csv filen betragtes af applikationen <strong>altid</strong> som 
                    header. Dine CSV filer skal derfor altid indeholde en første række 
                    med kolonnenavne.
                </p>
                <p>
                    Kun CSV filer placeret i mappen <?php echo SDFE_CSVFolder;?> og af typen (filendelse) <?php echo SDFE_CSVFileExtension ;?> fx minfil.<?php echo SDFE_CSVFileExtension ;?> medtages i applikationen.
                </p>
                <p>
                    Antallet af kolonner er i denne applikation defineret af CSV-filens header.
                    Det betyder, at skulle en CSV fil (ved en fejl?) indeholde 3 
                    kolonner i headerrækken, men 4 kolonner i de andre rækker, viser
                    applikationen kun de 3 kolonner. Omvendt, hvis headerrækken
                    indeholder 5 kolonner, men de resterende rækker kun 4, vises der 
                    5 kolonner. Den sidste kolonne vil så være tom.
                </p>
                <p>
                    Når en CSV fil ændres vil der automatisk blive taget en 
                    backup af filen. Backup filer lægges i mappen <?php echo SDFE_CSVFolderBackup;?>.
                    En backup erstatter seneste backup!
                </p>
                <h2>Konfiguration af applikation</h2>
                <p>
                    I filen config.php kan følgende sættes:
                <ul>
                    <li>SDFE_CSVSeparator: Angiver separatortegn fx ","</li>
                    <li>SDFE_CSVLineTerminator: Angiver tegn for linjeterminering fx "\n"</li>
                    <li>SDFE_CSVFileExtension: Angiver filendelsen på CSV filerne.</li>
                    <li>SDFE_CSVFolder: Mappenavn hvori CSV filer er. Mappen skal ligge i roden</li>
                    <li>SDFE_CSVFolderBackup: Mappenavn hvori CSV backup filer er. Mappen skal ligge i roden</li>
                </ul>
                </p>
                <h2>FAQ</h2>
                <h4>Hvordan ændrer jeg i en CSV fil?</h4>
                <p>
                Klik på den relevante fil i vestre side af applikation. Herefter vil dens
                indhold vises. Som udgangspunkt er alle rækkerne låst så du ikke
                ved en fejl får tastet noget forkert. Du skal derfor låse en række op før
                du kan redigere i indholdet. Du låser rækken op ved at klikke på 
                knappen med en lås i. Når din ændring er færdig anbefales det at 
                låse rækken igen ved at klikke på knappen med lås. Når du har foretaget
                <strong>alle</strong> dine rettelser klikker du på knappen "Gem ændringer" nederst på siden.
                </p>
                <h4>Hvordan sletter jeg en række i en CSV fil?</h4>
                <p>
                Vælg den relevante fil i vestre side af applikation. Herefter 
                klikker du på knappen med en skraldespand i den relevante række.  
                <strong>OBS</strong> Handlingen kan i applikationen ikke fortrydes! 
                Du kan dog undlade at gemme dine ændringer, hvis du fejlagtigt fik slettet en række.
                </p>
                <h4>Hvordan tilføjer jeg nye CSV filer eller sletter eksisterende CSV filer?</h4>
                <p>
                    Det kan du ikke gøre via denne applikation. Gå i stedet direkte 
                    på serveren med fjernskrivebord, SSH eller lign. og tilføj/slet filer. 
                </p>
                <h4>Hvordan ændrer jeg et kolonnenavn?</h4>
                <p>
                    Det kan du ikke gøre via denne applikation. Gå i stedet direkte 
                    på serveren med fjernskrivebord, SSH eller lign. og lav din ændring. 
                </p>
                <h4>Hvordan sletter/tilføjer jeg en kolonne?</h4>
                <p>
                    Det kan du ikke gøre via denne applikation. Gå i stedet direkte 
                    på serveren med fjernskrivebord, SSH eller lign. og lav din ændring. 
                </p>
            </div>
        </div>
    </div><!-- /.container -->
    <script src="js/jquery-1.11.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>