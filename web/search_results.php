 <!DOCTYPE html>
<html>
    <head>
       
        <link type="text/css" rel="stylesheet" href="main.css"/>
        <title>ATLAS: Database of TCR-pMHC affinities and structures</title>    
    </head>
    <body>
        <?php require 'ATLAS_functions.php'; ?>
        <div id="header">
            <img class="logo" src="atlas_img.png"  />

        </div>
        <div class="nav">
           <ul>
                <li><a class="active" href="./">Home</a></li>
                <li><a  href="downloads.php">Downloads</a></li>
                <li><a  href="resources.html">Resources</a></li>
                <li><a  href="references.html">References</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </div>
        <div class="results">
            <?php  
            $link = database_connect();
            if(isset($_POST['SEL'])) {

                // Get all parameters from display form
                if (isset($_POST['disp_all'])) {
                    $query_columns = '*';
                    $col_count = 33;
                }
                else if (isset($_POST['opts'])) {
                    $query_columns = join(', ',$_POST['opts']);
                    $col_count = count($_POST['opts']);
                }

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
                        $TRAV_result = mysqli_query($link, $query) or die(mysqli_error());
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
                        $TRBV_result = mysqli_query($link, $query) or die(mysqli_error());
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
                        $MHCclass_result = mysqli_query($link, $query) or die(mysqli_error());
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
                    $query = "SELECT {$query_columns} FROM Mutants;";
                    
                }
                else {
                    $query = "SELECT {$query_columns} FROM Mutants WHERE {$where_query};";
                }
                $result=mysqli_query($link, $query) or die(mysqli_error());   
            }
            ?>
            <br><br>
            <table border = '1px'>
                <thead>
                    <tr>
                        <th colspan='<?php echo $col_count; ?>'> Results </th>
                    </tr>
                    <tr
>                    <?php
                    if (isset($_POST['disp_all'])) {
                        $query = "SHOW COLUMNS FROM Mutants";
                        $col_result=mysqli_query($link, $query) or die(mysqli_error($link));
                        $i = 0;
                        while($row=mysqli_fetch_array($col_result)) {
                            $display_opts[$i] = $row['Field'];
                            $i++;
                        }
                        for ($i=0; $i<$col_count; $i++) {
                            ?> <th> <?php echo $display_opts[$i]; ?> </th>
                        <?php }
                        $columns = $display_opts;
                    }
                    else if (isset($_POST['opts'])) {
                        for ($i=0; $i<$col_count; $i++) {
                            ?> <th> <?php echo $_POST['opts'][$i]; ?> </th>
                        <?php }
                        $columns = $_POST['opts'];
                    } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        for ($i=0; $i<$col_count; $i++) {
                            echo "<td>";
                            echo $row[$columns[$i]];
                            echo "</td>"; 
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>

            </table>

        </div>      
   </body>
</html>