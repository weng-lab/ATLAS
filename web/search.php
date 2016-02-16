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
			            <li class="active"><a href="search.php">Search</a></li>
			           	<li><a  href="downloads.php">Downloads</a></li>
                		<li><a href="contact.html">Contact</a></li>
	            	</ul>
	          	</div>
	        </div>
        </nav>
        <div class="container">
        	<img class="my_logo" src="atlas_logo_v1.png" />
        	<br></br>
    		<div class = "well">
    			<p> ATLAS (Altered TCR Ligand Affinities and Structures) is a database containing wild type and mutant binding affinities for all TCRs for which TCR-pMHC structures are available. It is available for training and evalutaing next generation TCR-pMHC scoring functions.</p>
    		</div>
    	</div>

    	<!-- Search -->
    	<div class="container">
    		<form action="search_results.php" method="POST" role="form">
	    		<div class ="row">
	    			<div class="col-sm-3">
	    				<div class="panel panel-default">
	    					<div class="panel-heading">
	    						<h3 class="panel-title">TCR </h3>
	    					</div>
	    					<div class="panel-body">
				    			<div class="form-group">

					    			<?php $link = database_connect(); ?>
					    			<!-- Select TCR -->
					                <?php
					                $query="SELECT * FROM TCRs";
					                $result=mysqli_query($link, $query) or die(mysqli_error($link));
					                $i = 0;
					                while($row=mysqli_fetch_array($result)) {
					                    $TCRnames[$i] = $row['TCRname'];
					                    $i++;
					                }
					                ?>
					                <label for="Name" > Name: </label>
					                <select class="form-control" name="TCR">
					                    <option>all</option>
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
					                    ?>
					                </select>

					                <!-- Select TRAV -->
					                <?php
					                $query="SELECT TRAV from TCRs";
					                $result=mysqli_query($link, $query) or die(mysqli_error());
					                $i = 0;
					                while($row=mysqli_fetch_array($result)) {
					                    $TRAVnames[$i]= $row['TRAV'];
					                    $i++;
					                }
					                $TRAVnames = array_values(array_unique($TRAVnames));
					                ?>
					                <label for="TRAV" > TRAV: </label>
					                <select class="form-control" name="TRAV">
					                    <option>all</option>
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

				                    <!-- Select TRBV -->
					                <?php
					                $query="SELECT TRBV from TCRs";
					                $result=mysqli_query($link, $query) or die(mysqli_error());
					                $i = 0;
					                while($row=mysqli_fetch_array($result)) {
					                    $TRBVnames[$i]= $row['TRBV'];
					                    $i++;
					                }
					                $TRBVnames = array_values(array_unique($TRBVnames));
					                ?>
					                <label for="TRBV" > TRBV: </label>
					                <select class="form-control" name="TRBV">
					                    <option>all</option>
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
				                </div>
				            </div>
				        </div>
		            </div>
	    			<div class="col-sm-3">
	    				<div class="panel panel-default">
	    					<div class="panel-heading">
	    						<h3 class="panel-title"> MHC </h3>
	    					</div>
	    					<div class="panel-body">
				    			<div class="form-group">

				    				<!-- Select MHC class -->
					                <label for="MHCclass" > Class: </label>
					                <select class="form-control" name="MHCclass">
					                    <option>all</option>
					                    <option>I</option>
					                    <option>II</option>
					                </select>

					                <!-- Select HLA allele -->
					                <?php
					                $query="SELECT MHCname from MHCs";
					                $result=mysqli_query($link, $query) or die(mysqli_error());
					                $i = 0;
					                while($row=mysqli_fetch_array($result)) {
					                    $MHCnames[$i]= $row['MHCname'];
					                    $i++;
					                }
					                ?>
					                <label for="MHCname" > Allele: </label>
					                <select class="form-control" name="MHCname">
					                    <option>all</option>
					                    <?php
					                        for($j=0; $j<count($MHCnames); $j++) {
					                            ?>
					                            <option>
					                            <?php
					                            echo $MHCnames[$j];
					                            ?>
					                            </option>
					                            <?php
					                        }
					                    ?>
					                </select>
					            </div>
					        </div>
					    </div>
					</div>
					<div class="col-sm-3">
	    				<div class="panel panel-default">
	    					<div class="panel-heading">
	    						<h3 class="panel-title"> Energy </h3>
	    					</div>
	    					<div class="panel-body">
				    			<div class="form-group">
				    				<!--Select dG range -->
				    				<label for="dG" > &#916G < </label>
					                <input type="text" class="form-control" name="dG" value="0.00">
					            </div>
					        </div>
					    </div>
					</div>
					<div class="col-sm-3">
	    				<div class="panel panel-default">
	    					<div class="panel-heading">
	    						<h3 class="panel-title"> Peptide </h3>
	    					</div>
	    					<div class="panel-body">
				    			<div class="form-group">
				    				<!--Select Peptide sequence -->
				    				<label for="peptide" > Peptide Sequence: </label>
					                <input type="text" class="form-control" name="peptide" value="all">
					            </div>
					        </div>
					    </div>
					</div>
				</div>

        		<input type="submit" name="SEL" class="btn btn-default" value="Search"><br><br>
        	</form> 
        </div>  


	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../../bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>


	</body>
</html>