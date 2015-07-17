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

        <div id="intro" >
            <h2> Welcome to the ATLAS Database </h2>
            <p> ATLAS (Altered TCR Ligand Affinities and Structures) is a database containing wild type and mutant binding affinities for all TCRs for which TCR-pMHC structures are available. It is available for training and evalutaing next generation TCR-pMHC scoring functions.</p>    
        </div>
        <div class="search">
            <h2> Search</h2>
            <?php $link = database_connect(); ?>
            <form method="POST" action="search_results.php">
                <?php
                // Select TCR
                $query="SELECT * FROM TCRs";
                $result=mysqli_query($link, $query) or die(mysqli_error($link));
                $i = 0;
                while($row=mysqli_fetch_array($result)) {
                    $TCRnames[$i] = $row['TCRname'];
                    $i++;
                }
                ?>
                TCR: 
                <select name="TCR">
                <?php
                    for($j=0;$j<count($TCRnames);$j++) {
                        ?>
                        <option>
                        <?php 
                        echo $TCRnames[$j];
                        ?>
                        </option>
                        <?php
                    }
                ?></select>
                <input type="submit" name="TCRsel" value="Search"><br><br>
                <?php
                //Select TRAV
                $query="SELECT TRAV from TCRs";
                $result=mysqli_query($link, $query) or die(mysqli_error());
                $i = 0;
                while($row=mysqli_fetch_array($result)) {
                    $TRAVnames[$i]= $row['TRAV'];
                    $i++;
                }
                $TRAVnames = array_values(array_unique($TRAVnames));
                ?>
                TRAV:
                <select name="TRAV">
                <?php
                    for($j=0; $j<count($TRAVnames); $j++) {
                        ?>
                        <option>
                        <?php
                        echo $TRAVnames[$j];
                        ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                <input type="submit" name="TRAVsel" value="Search"><br><br>
                 <?php
                //Select TRBV
                $query="SELECT TRBV from TCRs";
                $result=mysqli_query($link, $query) or die(mysqli_error());
                $i = 0;
                while($row=mysqli_fetch_array($result)) {
                    $TRBVnames[$i]= $row['TRBV'];
                    $i++;
                }
                $TRBVnames = array_values(array_unique($TRBVnames));
                ?>
                TRBV:
                <select name="TRBV">
                <?php
                    for($j=0; $j<count($TRBVnames); $j++) {
                        ?>
                        <option>
                        <?php
                        echo $TRBVnames[$j];
                        ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                <input type="submit" name="TRBVsel" value="Search"><br><br>
            </form>   
        </div>
	</body>
</html>