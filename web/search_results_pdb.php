<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css">
<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script> 
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.10/sorting/num-html.js"></script>

<script type="text/javascript" class="init">
    
$(document).ready(function() {
    $('#result_table').DataTable( {
        searching: false,
        processin: true,
        "columnDefs": [
                // fix sorting of Kd column
                { "type": "num-html", targets: 8 }
            ]
        });
} );
</script>
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
                        <li class="active"><a href="./">Home</a></li>
                        <li><a href="search.php">Search</a></li>
                        <li><a  href="downloads.php">Downloads</a></li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a href="https://github.com/weng-lab/ATLAS">Github</a></li>
                        <li><a href="help.php">Help</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- <div class="container">
            <img class="my_logo" src="atlas_logo_v1.png" />
            <br></br>
        </div> -->
        <div class="container">
            <div class= "page-header">
                <h3>Search Results</h3>
            </div>
        </div>

        <?php  
        $link = database_connect();
        // Check if input is valid     
        if(isset($_GET['pdbid']) && $_GET['pdbid'] != '') {

            // Set query columns
            $query_columns = array("TCRname", "TCR_mut", "TCR_mut_chain", "MHCname", "MHC_mut", "MHC_mut_chain", "PEPseq", "PEP_mut", "Kd_microM", "DeltaG_kcal_per_mol", "true_PDB", "template_PDB", "PMID"); 
            $query_columns_str = join(', ', $query_columns);
            $col_count = 13;

            // Get all parameters from search form
            $search_params = "(true_PDB LIKE '%" . $_GET['pdbid'] . 
                "%' OR template_PDB LIKE '%" . $_GET['pdbid'] . "%')";

            $query = "SELECT {$query_columns_str} FROM Mutants WHERE {$search_params};";
        
            $result=mysqli_query($link, $query) or die(mysqli_error($link)); 
            ?>
            <div class="container">
                <table id= "result_table" class= "table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>TCR name</th>
                            <th>TCR mutation</th>
                            <th>TCR mutation chain</th>
                            <th>MHC allele</th>
                            <th>MHC mutation</th>
                            <th>MHC mutation chain</th>
                            <th>Peptide</th>
                            <th>Peptide mutation</th>
                            <th><span style="white-space: nowrap;">K<sub>D</sub> (&#956M)</span></th>
                            <th><span style="white-space: nowrap;">&#916G (kcal mol<sup>-1</sup>)</span></th>
                            <th>PDB</th>
                            <th>Template PDB</th>
                            <th>PMID</th>
                        </tr>
                        <?php 
                        // Write results to string for download
                        $download_results = join("\t", $query_columns) . "\n"; ?>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            for ($i=0; $i<$col_count; $i++) {
                                echo "<td>";
                                if ($row[$query_columns[$i]] == "PMID") { 
                                    $download_results .= $row[$query_columns[$i]];
                                } else {
                                    $download_results .= $row[$query_columns[$i]] . "\t";
                                }
                                if ($query_columns[$i] == "MHCname") {
                                    ?>
                                    <span style="white-space: nowrap;"> 
                                    <?php 
                                    $MHCname_arr = explode("|", $row[$query_columns[$i]]);
                                    foreach ($MHCname_arr as $allele) {
                                        echo $allele;
                                        ?> <br></br> <?php
                                    }
                                    ?>
                                    </span>
                                    <?php
                                }
                                elseif ($query_columns[$i] == "PMID") {
                                    echo '<a href="http://www.ncbi.nlm.nih.gov/pubmed/' . $row[$query_columns[$i]] . '">'. $row[$query_columns[$i]] . '</a>';
                                }
                                elseif ($query_columns[$i] == "true_PDB") {
                                    echo '<a href="3D_viewer.php?pdb=' . $row[$query_columns[$i]] . '">'. $row[$query_columns[$i]] . '</a>';
                                }
                                elseif ($query_columns[$i] == "template_PDB") {
                                    if ($row['MHC_mut_chain'] == "") {
                                        $tpdb_mhc_chain ="nan";
                                    } else {
                                        $tpdb_mhc_chain = preg_replace('/\s+/', '', $row['MHC_mut_chain']);
                                        $tpdb_mhc_chain = preg_replace('/\|/', '.', $tpdb_mhc_chain);
                                    }
                                    if ($row['TCR_mut_chain'] == "") {
                                        $tpdb_tcr_chain ="nan";
                                    } else {
                                        $tpdb_tcr_chain = preg_replace('/\s+/', '', $row['TCR_mut_chain']); 
                                        $tpdb_tcr_chain = preg_replace('/\|/', '.', $tpdb_tcr_chain);
                                    }
                                    $tpdb_pdb = preg_replace('/\s+/', '', $row[$query_columns[$i]]);
                                    $tpdb_mhc = preg_replace('/\s+/', '', $row['MHC_mut']);
                                    $tpdb_mhc = preg_replace('/\|/', '.', $tpdb_mhc);
                                    $tpdb_tcr = preg_replace('/\s+/', '', $row['TCR_mut']);
                                    $tpdb_tcr = preg_replace('/\|/', '.', $tpdb_tcr);
                                    $tpdb_pep = preg_replace('/\s+/', '', $row['PEP_mut']);
                                    $tpdb_pep = preg_replace('/\|/', '.', $tpdb_pep);

                                    echo '<a href="3D_viewer_designed.php?pdb=' . $tpdb_pdb . '&mhc_mut='. $tpdb_mhc 
                                    . '&mhc_chain='. $tpdb_mhc_chain . '&tcr_mut='. $tpdb_tcr . '&tcr_chain='. $tpdb_tcr_chain 
                                    . '&pep_mut=' . $tpdb_pep . '">'. $tpdb_pdb . '</a>';

                                }
                                else {
                                    echo $row[$query_columns[$i]];
                                }
                                echo "</td>"; 
                            }
                            $download_results .= "\n";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class= "container">
                <?php
                echo '
                <form action="tables/results_table.php" method="POST">
                <input type="hidden" name="results" value="' . $download_results . '"> 
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-download"></span> Download Results
                </button>
                </form>';
                ?>
            </div>
            <br><br>
        <?php 
        }
        else {
            ?> 
            <div class = "well">
                <p> Invalid search selection </p>
            </div>
        <?php }
        ?>
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

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>      
   
   </body>
</html>