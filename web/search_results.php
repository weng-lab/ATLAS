<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="../../bootstrap/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <li class="active"><a href="./">Home</a></li>
                        <li><a  href="downloads.php">Downloads</a></li>
                        <li><a  href="references.html">References</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <img class="my_logo" src="atlas_logo_v1.png" />
            <br></br>
        </div>
        <?php  
        $link = database_connect();
        if(isset($_POST['SEL'])) {

            // Set query columns
            $query_columns = array("TCRname", "TCR_mut", "TCR_mut_chain", "MHCname", "MHC_mut", "MHC_mut_chain", "PEPseq", "PEP_mut", "Kd_microM", "DeltaG_kcal_per_mol", "true_PDB", "template_PDB", "PMID"); 
            $query_columns_str = join(', ', $query_columns);
            $col_count = 13;


            // Get all parameters from search form
            $search_params = array();
            if (isset($_POST['TCR'])) {
                if ($_POST['TCR'] != 'all') {
                    $search_params[] = "(TCRname = '" . $_POST['TCR'] . "')";
                }           
            }
            if (isset($_POST['TRAV'])) {
                if ($_POST['TRAV'] != 'all') {                    
                    $query = "SELECT TCRname FROM TCRs WHERE TRAV='" . $_POST['TRAV'] . "';";
                    $TRAV_result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $i=0;
                    while($row = mysqli_fetch_array($TRAV_result)) {
                        $TRAVtcrs[$i] = $row['TCRname'];
                        $i++;
                    }
                    $or_string = '';
                    for ($i = 0; $i < count($TRAVtcrs); $i++) {
                        $or_string .= "TCRname = '" . $TRAVtcrs[$i] . "'";
                        if ($i < count($TRAVtcrs)-1) {
                            $or_string.= " OR ";
                        }
                    }
                    $search_params[] = "(" . $or_string . ")";
                }           
            }
            if (isset($_POST['TRBV'])) {
                if ($_POST['TRBV'] != 'all') {
                    $query = "SELECT TCRname FROM TCRs WHERE TRBV='" . $_POST['TRBV'] . "';";
                    $TRBV_result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $i=0;
                    while($row = mysqli_fetch_array($TRBV_result)) {
                        $TRBVtcrs[$i] = $row['TCRname'];
                        $i++;
                    }
                    $or_string = '';
                    for ($i = 0; $i < count($TRBVtcrs); $i++) {
                        $or_string .= "TCRname = '" . $TRBVtcrs[$i] . "'";
                        if ($i < count($TRBVtcrs)-1) {
                            $or_string.= " OR ";
                        }
                    }
                    $search_params[] = "(" . $or_string . ")";
                }           
            }

            if (isset($_POST['MHCclass'])) {
                if ($_POST['MHCclass'] != 'all') {
                    $query = "SELECT MHCname FROM MHCs WHERE class='" . $_POST['MHCclass'] . "';";
                    $MHCclass_result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $i=0;
                    while($row = mysqli_fetch_array($MHCclass_result)) {
                        $class_mhcs[$i] = $row['MHCname'];
                        $i++;
                    }
                    $or_string = '';
                    for ($i = 0; $i < count($class_mhcs); $i++) {
                        $or_string .= "(MHCname LIKE '%" . $class_mhcs[$i] . 
                                "%' OR MHCname_PDB LIKE '%" . $class_mhcs[$i] . "%')";
                        if ($i < count($class_mhcs)-1) {
                            $or_string.= " OR ";
                        }
                    }
                    $search_params[] = "(" . $or_string . ")";
                }           
            }

            if (isset($_POST['MHCname'])) {
                if ($_POST['MHCname'] != 'all') {
                    $search_params[] = "(MHCname LIKE '%" . $_POST['MHCname'] .
                        "%' OR MHCname_PDB LIKE '%" . $_POST['MHCname'] . "%')";
                }
            }

            if (isset($_POST['dG'])) {
                $search_params[] = "(DeltaG_kcal_per_mol < '" . $_POST['dG'] . "')";
            }

            $where_query = join(' AND ', $search_params);
            if (empty($search_params)) {
                $query = "SELECT {$query_columns_str} FROM Mutants;";
                
            }
            else {
                $query = "SELECT {$query_columns_str} FROM Mutants WHERE {$where_query};";
            }
            $result=mysqli_query($link, $query) or die(mysqli_error($link));   
        }
        ?>
        <div class="container">
            <table class= "table table-bordered table-striped">
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
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        for ($i=0; $i<$col_count; $i++) {
                            echo "<td>";
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
                            else {
                                echo $row[$query_columns[$i]];
                            }
                            echo "</td>"; 
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>      
   
   </body>
</html>