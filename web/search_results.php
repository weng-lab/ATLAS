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
                if (isset($_POST['disp_all'])) {
                    $query_columns = '*';
                    $col_count = 33;
                }
                else if (isset($_POST['opts'])) {
                    $query_columns = join(', ',$_POST['opts']);
                    $col_count = count($_POST['opts']);
                }

                if (isset($_POST['TCR'])) {
                    $TCR_id = $_POST['TCR'];
                    if ($TCR_id == 'all') {
                        $query = "SELECT " . $query_columns . 
                        " FROM Mutants;";
                        
                    }
                    else {
                        $query = "SELECT " . $query_columns . 
                        " FROM Mutants WHERE TCRname = '" . $TCR_id . "';";
                    }           
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
                    <tr>
                    <?php
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