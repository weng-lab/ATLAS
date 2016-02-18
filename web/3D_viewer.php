<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	
    	<link href="../bootstrap/less/bootstrap.css" rel="stylesheet">
    	<link href="my_css.css" rel="stylesheet"/>
        <script src="../../../bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script> 
    	<title>ATLAS: Database of TCR-pMHC affinities and structures</title>	
    </head>
    <body>
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
                        <li class="active"><a href="./">Home</a></li>
                        <li><a href="search.php">Search</a></li>
                        <li><a  href="downloads.php">Downloads</a></li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a href="help.php">Help</a></li>
                    </ul>
                </div>
            </div>
        </nav>
       <div class="container">
            <div class="page-header">
                <h3><?php echo $_GET['pdb'] ?></h3>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">PV viewer </h3>
                        </div>
                        <div class="panel-body">
                            <div id="viewer"></div>
                            <script type='text/javascript' src='bio-pv.min.js'></script>

<<<<<<< HEAD
    <script type='text/javascript'>
    function loadPDB() {
      var pdbfile = "../all/" + "<?php echo $_GET['pdb']; ?>" + ".pdb";
        pv.io.fetchPdb(pdbfile, function(structure) {
            var geom = viewer.cartoon('protein', structure);
            viewer.centerOn(structure);
            viewer.fitTo(structure);
            geom.colorBy(pv.color.byChain());
        });
    }
    document.addEventListener('DOMContentLoaded', loadPDB);
    </script>
=======
                            <script type='text/javascript'>
                            var options = {
                            width: 525,
                            height: 600,
                            antialias: true,
                            quality : 'medium'
                            };
                            var viewer = pv.Viewer(document.getElementById('viewer'), options);
                            </script>
>>>>>>> 5bd12a03d426a890458c38c092282d30488ffd80

                            <script type='text/javascript'>
                            function loadPDB() {
                                var pdbfile = "../structures/true_pdb/" + "<?php echo $_GET['pdb']; ?>" + ".pdb";
                                pv.io.fetchPdb(pdbfile, function(structure) {
                                    var geom = viewer.cartoon('protein', structure);
                                    viewer.centerOn(structure);
                                    viewer.fitTo(structure);
                                    //geom.colorBy(pv.color.byChain(pv.color.gradient(['lightcyan', 'darkblue'])));
                                    geom.colorBy(pv.color.byChain());
                                });
                            }
                            document.addEventListener('DOMContentLoaded', loadPDB);
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Download </h3>
                        </div>
                        <div class="panel-body">
                            <p> PDB <?php echo $_GET['pdb'];?>:</p>
                            <a href= <?php echo "../structures/true_pdb/" . $_GET['pdb'] .".pdb";?> class="btn btn-primary">
                                <span class="glyphicon glyphicon-download"></span> PDB structure
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
    </body>
</html>