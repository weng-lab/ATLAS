<!DOCTYPE html>
<html lang="en">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-90417810-1', 'auto');
  ga('send', 'pageview');

</script>
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
                    </button>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="./">Home</a></li>
                        <li><a href="search.php">Search</a></li>
                        <li class="active"><a  href="downloads.php">Downloads</a></li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a href="https://github.com/weng-lab/ATLAS">Github</a></li>
                        <li><a href="help.php">Help</a></li>

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
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">TCR genes </h3>
                        </div>
                        <div class="panel-body">
                            <p> Table providing TRAV and TRBV genes for TCR clonotypes <p>
                            <a href="tables/TCRs.xlsx" class="btn btn-primary">
                                <span class="glyphicon glyphicon-download"></span> TCR gene table
                            </a>
                        </div> 
                    </div>
                </div>
                 <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">MHC class </h3>
                        </div>
                        <div class="panel-body">
                            <p> Table providing MHC class (I or II) for MHC alleles in ATLAS <p>
                            <a href="tables/MHCs.xlsx" class="btn btn-primary">
                                <span class="glyphicon glyphicon-download"></span> MHC class table
                            </a>
                        </div> 
                    </div>
                </div>
                <!-- <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Authors </h3>
                        </div>
                        <div class="panel-body">
                            <p> Authors, Article Titles, Journals and Pubmed IDs for ATLAS data<p>
                            <a href="tables/Authors.xlsx"  class="btn btn-primary">
                                <span class="glyphicon glyphicon-download"></span> Authors table
                            </a>
                        </div> 
                    </div>
                </div> -->
            </div>
            <hr>
            <div class="row">
                 <div class="col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"> ATLAS </h3>
                        </div>
                        <div class="panel-body">
                            <p> Full ATLAS table including TCR/peptide/MHC mutations, IDs for template PDBs, 
                                binding data, and experimental details<p>
                            <a href="tables/ATLAS.xlsx"  class="btn btn-primary">
                                <span class="glyphicon glyphicon-download"></span> ATLAS table
                            </a>
                        </div> 
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"> TCR-pMHC structures </h3>
                        </div>
                        <div class="panel-body">
                            <p> Dataset of all TCR-pMHC structures in ATLAS with the following adjustments: 
                                renaming of chains, truncation of chains to binding interface, and removal of 
                                water molecules. For ATLAS entries lacking full 3D strucutres, models were generated
                                from template structures using the Rosetta protein modeling suite<p>
                            <a href="tables/tcr_structure_database.tar"  class="btn btn-primary">
                                <span class="glyphicon glyphicon-download"></span> TCR-pMHC structures
                            </a>
                        </div> 
                    </div>
                </div>
            </div>
            <hr>
            <footer>
                <div class= "row">
                    <div class= "col-sm-4" align= "center">
                        <img src="logos/umasslogoformal.gif" width='180' />
                    </div>
                    <div class= "col-sm-4" align= "center">
                        <img src="logos/1_university_mark.jpg" width='225'/>
                    </div>
                    <div class= "col-sm-4" align= "center">
                        <img src="logos/IBBR-Logo_Long.png" width='250' />
                    </div>
                </div>
                <br></br>
            </footer>
        </div>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

    </body>
</html>