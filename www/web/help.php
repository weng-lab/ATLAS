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
                        <li><a href="downloads.php">Downloads</a></li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a href="https://github.com/weng-lab/ATLAS">Github</a></li>
                        <li class="active"><a href="help.php">Help</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- <div class="container">
            <img class="my_logo" src="atlas_logo_v1.png" />
        </div> -->
        <div class="container">
            <div class="page-header">
                <h3>Search Example</h3>
            </div>

            <div class="col-sm-7">
                <br></br>
                <img class="img-responsive" src="help/search_ex2.png" />
            </div>
            <div class="col-sm-5">
                <ul class = "list-group">
                    <li class="list-group-item"> 
                        <img src="help/1.png" width="40" align="left" hspace="15">
                        <p> Search by TCR name or by T Cell receptor alpha/beta variable genes (TRAV/TRBV)</p>
                        <p class="text-muted"><i>Name: A6</i> </p>
                    </li>
                    <li class="list-group-item"> 
                        <img src="help/2.png" width="40" align="left" hspace="15"/>
                        <p> Search by MHC allele or view all entries for an MHC class</p>
                        <p class="text-muted"><i>Allele: HLA-A*02:01</i></p> 
                    </li>
                    <li class="list-group-item"> 
                        <img src="help/3.png" width="40" align="left" hspace="15"/>
                        <p> Search for entries with experimental binding energies below input 
                            &#916G in kcal mol<sup>-1</sup></p>
                        <p class="text-muted"><i> &#916G (kcal mol<sup>-1</sup>) < -6.00</i></p>   
                        </li>
                    <li class="list-group-item"> 
                        <img src="help/4.png" width="40" align="left" hspace="15"/>
                        <p> Search for entries containing a peptide with input amino acid sequence.
                            Sub-sequences are allowed. Case-insensitive. 
                        <p class="text-muted"><i> Peptide sequence: LFGYPVY</i></p>   
                    </li>
                </ul>
            </div>
        </div>
        <div class="container">
            <h3>Results Example</h3>
            <hr>
            <div class="col-sm-5">
                <div class="well">
                    <p> Each ATLAS entry (row) of the search results corresponds to a unique TCR-pMHC complex
                        with an experimentally determined binding affinity and a 3D structure or similar structure 
                        which can be used as a template for design.</p>
                </div>
                <ul class = "list-group">
                    <li class="list-group-item"> 
                        <img src="help/1.png" width="40" align="left" hspace="15">
                        <p> The <i class="text-muted">PDB</i> column refers to the Protein Data Bank (PDB) ID for a structure
                            matching the TCR-pMHC complex with the reported experimental binding affinity,
                             <i class="text-muted">&#916G (kcal mol<sup>-1</sup>)</i>.</p>
                    </li>
                    <li class="list-group-item"> 
                        <img src="help/2.png" width="40" align="left" hspace="15">
                        <p>The <i class="text-muted">Template PDB</i> column refers to the PDB ID for a template structure
                            such that a TCR-pMHC complex matching the reported binding affinity may be generated from this 
                            structure by modeling the mutations described in the <i class="text-muted">TCR mutation</i>, 
                            <i class="text-muted">MHC mutation</i>, and <i class="text-muted">Peptide mutation</i> columns.
                            The <i class="text-muted">TCR mutation chain </i> and <i class="text-muted">MHC mutation chain</i> refer
                            to the protein chain (A for alpha and B for beta) carrying the TCR and MHC mutations, respectively.
                            </p>
                    </li>
                </ul>
            </div>
            <div class="col-sm-7">
                <img class="img-responsive" src="help/search_results2.png" />
            </div>
        </div>
        <div class="container">
            <h3>Structure Download</h3>
            <hr>
            <div class="col-sm-7">
                <img class="img-responsive" src="help/1ao7.png" />
            </div>
            <div class="col-sm-5">
                <div class="well">
                    <p> Selecting the Template PDB for the D26W TCR mutant in the results example brings you
                        to the PV-JavaScript Protein Viewer allowing you to view the selected structure and
                        the Rosetta designed mutations. The individual template structures and Rosetta designed structures
                        are available for download. For uniformity all structures carry the following adjustments: 
                        renaming of chains, truncation of chains to binding interface, and removal of water moleculues.
                    </p>
                </div>
            </div>
        </div>
        <div class="container">
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