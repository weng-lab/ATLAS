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
                        <li><a  href="downloads.php">Downloads</a></li>
                        <li><a href="contact.html">Contact</a></li>
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
                            <div id=viewer></div>
                        </div> 
                    </div>
                </div>
    <script src="../../../bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>      
   
   </body>
   <script type='text/javascript' src='bio-pv.min.js'></script>

   <script type='text/javascript'>
    var options = {
    width: 525,
    height: 600,
    antialias: true,
    quality : 'medium'
    };
    var viewer = pv.Viewer(document.getElementById('viewer'), options);
    </script>

    <script type='text/javascript'>
    function loadPDB() {
        var pdbfile = "../structures/designed_pdb/" + 
        "<?php echo $_GET['pdb'];?>" + "_" +
        "<?php echo $_GET['mhc_mut'];?>" + "_" +
        "<?php echo $_GET['mhc_chain'];?>" + "_" +
        "<?php echo $_GET['tcr_mut'];?>" + "_" +
        "<?php echo $_GET['tcr_chain'];?>" + "_" +
        "<?php echo $_GET['pep_mut'];?>" +
        ".pdb";

        pv.io.fetchPdb(pdbfile, function(structure) {
            var geom = viewer.cartoon('protein', structure);
            viewer.centerOn(structure);
            viewer.fitTo(structure);
            geom.colorBy(pv.color.byChain(pv.color.gradient(['lightcyan', 'darkblue'])));
        });
    }
    document.addEventListener('DOMContentLoaded', loadPDB);
    </script>

</html>