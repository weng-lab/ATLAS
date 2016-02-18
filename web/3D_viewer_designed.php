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

                                var pep_mut = "<?php echo $_GET['pep_mut'];?>".split(".");
                                var mhc_mut = "<?php echo $_GET['mhc_mut'];?>".split(".");
                                var mhc_chain = "<?php echo $_GET['mhc_chain'];?>".split(".");
                                var tcr_mut = "<?php echo $_GET['tcr_mut'];?>".split(".");
                                var tcr_chain = "<?php echo $_GET['tcr_chain'];?>".split(".");

                                pv.io.fetchPdb(pdbfile, function(structure) {
                                    var geom = viewer.cartoon('protein', structure);
                                    viewer.centerOn(structure);
                                    viewer.fitTo(structure);
                                    // geom.colorBy(pv.color.byChain(pv.color.gradient(['lightcyan', 'darkblue'])));
                                    geom.colorBy(pv.color.byChain());
                                    // display peptide mutations
                                    if (pep_mut[0] != "WT") {
                                        for (i=0; i < pep_mut.length; i++) {
                                            // get res number
                                            var res_num = pep_mut[i].substring(1, pep_mut[i].length -1);
                                            var mutant = structure.select({cname:'C', rnum: parseInt(res_num)});
                                            viewer.ballsAndSticks('mutant', mutant);
                                            var carbonAlpha = structure.atom('C.' + res_num + '.CA');
                                            viewer.label('label', pep_mut[i], carbonAlpha.pos());
                                        }
                                    }
                                    // display mhc mutations
                                    if (mhc_mut[0] != "WT") {
                                        for (i=0; i < mhc_mut.length; i++) {
                                            // get res number and chain
                                            res_num = mhc_mut[i].substring(1, mhc_mut[i].length -1);
                                            var chain = mhc_chain[i];
                                            mutant = structure.select({cname:chain, rnum: parseInt(res_num)});
                                            viewer.ballsAndSticks('mutant', mutant);
                                            carbonAlpha = structure.atom(chain + '.' + res_num + '.CA');
                                            viewer.label('label', mhc_mut[i], carbonAlpha.pos());
                                        }
                                    }
                                    // display tcr mutations
                                    var tcr_chain_map = [];
                                    tcr_chain_map['A'] = 'D';
                                    tcr_chain_map['B'] = 'E';

                                    if (tcr_mut[0] != "WT") {
                                        for (i=0; i < tcr_mut.length; i++) {
                                            // get res number and chain
                                            res_num = tcr_mut[i].substring(1, tcr_mut[i].length -1);
                                            chain = tcr_chain_map[tcr_chain[i]];
                                            mutant = structure.select({cname:chain, rnum: parseInt(res_num)});
                                            viewer.ballsAndSticks('mutant', mutant);
                                            carbonAlpha = structure.atom(chain + '.' + res_num + '.CA');
                                            viewer.label('label', tcr_mut[i], carbonAlpha.pos());
                                        }
                                    }

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
                            <p>Template PDB <?php echo $_GET['pdb'];?>:</p>
                            <a href= <?php echo "../structures/true_pdb/" . $_GET['pdb'] .".pdb";?> class="btn btn-primary">
                                <span class="glyphicon glyphicon-download"></span> Template
                            </a>
                            <br></br>
                            <p> Designed model of <?php echo $_GET['pdb'] ?> with the following mutation(s): <p>
                                <ul class="list-group">
                                    <?php
                                    if ($_GET['tcr_mut'] == 'WT') {
                                        ?><li class="list-group-item">TCR mutations: None</li>
                                        <li class="list-group-item">TCR mutation chains: None</li>
                                        <?php }
                                    else {
                                        ?> <li class="list-group-item">TCR mutations: <?php echo $_GET['tcr_mut'];?> </li>
                                        <li class="list-group-item">TCR mutation chains: <?php echo $_GET['tcr_chain'];?></li>
                                        <?php }
                                    if ($_GET['mhc_mut'] == 'WT') {
                                        ?><li class="list-group-item">MHC mutations: None</li>
                                        <li class="list-group-item">MHC mutation chains: None</li>
                                        <?php }
                                    else {
                                        ?><li class="list-group-item">MHC mutations: <?php echo $_GET['mhc_mut'];?></li>
                                    <li class="list-group-item">MHC mutation chains: <?php echo $_GET['mhc_chain'];?></li>
                                    <?php }
                                    if ($_GET['pep_mut'] == 'WT') {
                                        ?><li class="list-group-item">Peptide mutations: None</li>
                                    <?php }
                                    else {
                                        ?><li class="list-group-item">Peptide mutations: <?php echo $_GET['pep_mut'];?></li>
                                    <?php } ?>
                                </ul>
                                <a href= <?php echo "../structures/designed_pdb/" . $_GET['pdb'] . "_" . $_GET['mhc_mut'] . 
                                "_" . $_GET['mhc_chain'] . "_" . $_GET['tcr_mut'] . "_" . $_GET['tcr_chain'] . "_" . 
                                $_GET['pep_mut'] . ".pdb";?> class="btn btn-primary">                                
                                <span class="glyphicon glyphicon-download"></span> Designed 
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