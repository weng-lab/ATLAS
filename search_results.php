 <!DOCTYPE html>
<html>
    <head>
       
        <link type="text/css" rel="stylesheet" href="main.css"/>
        <title>ATLAS: Database of TCR-pMHC affinities and structures</title>    
    </head>
    <body>
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
            $config = parse_ini_file('../.mysqlpasswd.ini');
	    $link = mysqli_connect('localhost', $config['user'], 
				   $config['pass'], $config['db']);
            if (!$link)   
            {   
              $error = 'Unable to connect to the database server.';   
              include 'error.html.php';   
              exit();   
            }   
            if (!mysqli_set_charset($link, 'utf8'))   
            {   
              $output = 'Unable to set database connection encoding.';   
              include 'output.html.php';   
              exit();   
            }  
            if (!mysqli_select_db($link, 'atlas'))   
            {   
              $error = 'Unable to locate the atlas database.';   
              include 'error.html.php';   
              exit();   
            }
            if(isset($_POST['TCRsel'])) {
                $TCR_id=$_POST['TCR'];
                $query = "SELECT * FROM Mutants WHERE TCRname='$TCR_id'";
                $result=mysqli_query($link, $query) or die(mysqli_error());
                ?>
                <table border = '1px'>
                    <thead>
                        <tr>
                            <th colspan='11' > <?php echo $TCR_id ?> TCR</th>
                        </tr>
                        <tr>
                            <th> MHC name </th>
                            <th> Mutant </th>
                            <th> Kd </th>
                            <th> Peptide Sequence </th>
                            <th> WT PDB </th>
                            <th> Mutant PDB </th>
                            <th> CDR </th>
                            <th> WT CDR sequence </th>
                            <th> Chain </th>
                            <th> PubMed ID</th>
                            <th> Experimental Method </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_array( $result )) {
                            echo "<tr><td>";
                            echo $row['MHCname'];
                            echo "</td><td>";
                            echo $row['Mutant'];
                            echo "</td><td>";
                            echo $row['Kd'];
                            echo "</td><td>";
                            echo $row['PEPseq'];
                            echo "</td><td>";
                            echo $row['wtPDB'];
                            echo "</td><td>";
                            echo $row['mutPDB'];
                            echo "</td><td>";
                            echo $row['CDR'];
                            echo "</td><td>";
                            echo $row['wtCDRseq'];
                            echo "</td><td>";
                            echo $row['Chain'];
                            echo "</td><td>";
                            echo $row['PMID'];
                            echo "</td><td>";
                            echo $row['ExpMethod'];
                            echo "</td></tr>";
                        }
                    ?>
                    </tbody>
                </table>
            <?php
            }   
            if(isset($_POST['TRAVsel'])) {
                $TRAV_id=$_POST['TRAV'];
                $query1 = "SELECT TCRname FROM TCRs WHERE TRAV='$TRAV_id'";
                $result1=mysqli_query($link, $query1) or die(mysqli_error());
                $i=0;
                while($row = mysqli_fetch_array($result1)) {
                    $TRAVtcrs[$i] = $row['TCRname'];
                    $i++;
                }
                $or_string = '';
                for ($i = 0; $i < count($TRAVtcrs); $i++) {
                    $or_string .= "TCRname = '" . $TRAVtcrs[$i] . "'";
                    if ($i < count($TRAVtcrs)-1) {
                        $or_string.= " or ";
                    }
                }
                $query2 = "SELECT * FROM Mutants WHERE ". $or_string;
                $result2=mysqli_query($link, $query2) or die(mysqli_error());
                ?>
                <table border = '1px'>
                    <thead>
                        <tr>
                            <th colspan='12' > <?php echo $TRAV_id ?> Gene</th>
                        </tr>
                        <tr>
                            <th> TCR name </th>
                            <th> MHC name </th>
                            <th> Mutant </th>
                            <th> Kd </th>
                            <th> Peptide Sequence </th>
                            <th> WT PDB </th>
                            <th> Mutant PDB </th>
                            <th> CDR </th>
                            <th> WT CDR sequence </th>
                            <th> Chain </th>
                            <th> PubMed ID</th>
                            <th> Experimental Method </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_array( $result2 )) {
                            echo "<tr><td>";
                            echo $row['TCRname'];
                            echo "</td><td>";
                            echo $row['MHCname'];
                            echo "</td><td>";
                            echo $row['Mutant'];
                            echo "</td><td>";
                            echo $row['Kd'];
                            echo "</td><td>";
                            echo $row['PEPseq'];
                            echo "</td><td>";
                            echo $row['wtPDB'];
                            echo "</td><td>";
                            echo $row['mutPDB'];
                            echo "</td><td>";
                            echo $row['CDR'];
                            echo "</td><td>";
                            echo $row['wtCDRseq'];
                            echo "</td><td>";
                            echo $row['Chain'];
                            echo "</td><td>";
                            echo $row['PMID'];
                            echo "</td><td>";
                            echo $row['ExpMethod'];
                            echo "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            }   
            if(isset($_POST['TRBVsel'])) {
                $TRBV_id=$_POST['TRBV'];
                $query1 = "SELECT TCRname FROM TCRs WHERE TRBV='$TRBV_id'";
                $result1=mysqli_query($link, $query1) or die(mysqli_error());
                $i=0;
                while($row = mysqli_fetch_array($result1)) {
                    $TRBVtcrs[$i] = $row['TCRname'];
                    $i++;
                }
                $or_string = '';
                for ($i = 0; $i < count($TRBVtcrs); $i++) {
                    $or_string .= "TCRname = '" . $TRBVtcrs[$i] . "'";
                    if ($i < count($TRBVtcrs)-1) {
                        $or_string.= " or ";
                    }
                }
                $query2 = "SELECT * FROM Mutants WHERE ". $or_string;
                $result2=mysqli_query($link, $query2) or die(mysqli_error());
                ?>
                <table border = '1px'>
                    <thead>
                        <tr>
                            <th colspan='12' > <?php echo $TRBV_id ?> Gene</th>
                        </tr>
                        <tr>
                            <th> TCR name </th>
                            <th> MHC name </th>
                            <th> Mutant </th>
                            <th> Kd </th>
                            <th> Peptide Sequence </th>
                            <th> WT PDB </th>
                            <th> Mutant PDB </th>
                            <th> CDR </th>
                            <th> WT CDR sequence </th>
                            <th> Chain </th>
                            <th> PubMed ID</th>
                            <th> Experimental Method </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_array( $result2 )) {
                            echo "<tr><td>";
                            echo $row['TCRname'];
                            echo "</td><td>";
                            echo $row['MHCname'];
                            echo "</td><td>";
                            echo $row['Mutant'];
                            echo "</td><td>";
                            echo $row['Kd'];
                            echo "</td><td>";
                            echo $row['PEPseq'];
                            echo "</td><td>";
                            echo $row['wtPDB'];
                            echo "</td><td>";
                            echo $row['mutPDB'];
                            echo "</td><td>";
                            echo $row['CDR'];
                            echo "</td><td>";
                            echo $row['wtCDRseq'];
                            echo "</td><td>";
                            echo $row['Chain'];
                            echo "</td><td>";
                            echo $row['PMID'];
                            echo "</td><td>";
                            echo $row['ExpMethod'];
                            echo "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            }   
            ?>
        </div>      
   </body>
</html>