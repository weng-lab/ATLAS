<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="../bootstrap/less/bootstrap.css" rel="stylesheet">
        <link href="my_css.css" rel="stylesheet"/>
        <title>ATLAS: Database of TCR-pMHC affinities and structures</title>    
    </head>
    <body>
    <?php require 'ATLAS_functions.php'; ?>
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="./">Home</a></li>
                        <li class="active"><a  href="downloads.php">Downloads</a></li>
                        <li><a  href="references.html">References</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- <div class="container">
            <img class="my_logo" src="atlas_logo_v1.png" />
        </div> -->
        <div class="container">
            <div class="page-header">
                <h3>Full Datasets</h3>
            </div>
                
           <a href="tables/TCRs.xlsx" class="btn btn-lg btn-default">
                <span class="glyphicon glyphicon-download"></span> TCR table
            </a><br></br>
            <a href="tables/MHCs.xlsx" class="btn btn-lg btn-default">
                <span class="glyphicon glyphicon-download"></span> MHC table
            </a><br></br>
            <a href="tables/Mutants.xlsx" class="btn btn-lg btn-default">
                <span class="glyphicon glyphicon-download"></span> Mutants table
            </a><br></br>
            <a href="tables/Authors.xlsx" class="btn btn-lg btn-default">
                <span class="glyphicon glyphicon-download"></span> Authors table
            </a><br></br>
            <a href="tables/tcr_structure_database.tar" class="btn btn-lg btn-default">
                <span class="glyphicon glyphicon-download"></span> TCR-pMHC structures
            </a><br></br>
        </div>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

    </body>
</html>