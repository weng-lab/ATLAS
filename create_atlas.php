<?php             
	$conn = mysqli_connect('localhost', 'borrmant', 'sqlpsswd', 'atlas');
	if (!$conn) {
		die("Connection failed ". mysqli_connect_error() . "\n");
	}
	echo "Connected successfully\n";
	// MHCs
	$sql = "CREATE TABLE MHCs(
			MHCname VARCHAR(20) NOT NULL,
			wtPDB CHAR(4) NOT NULL,
			PRIMARY KEY (MHCname));";
	if (mysqli_query($conn, $sql)) {
		echo "Table created successfully\n";
	}
	else {
		echo "Error creating table: " . mysqli_error($conn) . "\n";
	}
	// Write to table
	$sql = "LOAD DATA LOCAL INFILE '/cygwin64/home/Tyler/Research/TCR/ATLAS/MHCs.txt' INTO TABLE MHCs;";
	if (mysqli_query($conn, $sql)) {
		echo "Data loaded successfully\n";
	}
	else {
		echo "Error loading data: " . mysqli_error($conn) . "\n";
	}
	// Authors
	$sql = "CREATE TABLE Authors(
			PMID INT(8) NOT NULL,
			Authors TEXT NOT NULL,
			Title TEXT NOT NULL,
			Journal VARCHAR(100) NOT NULL,
			PRIMARY KEY (PMID));";
	if (mysqli_query($conn, $sql)) {
		echo "Table created successfully\n";
	}
	else {
		echo "Error creating table: " . mysqli_error($conn) . "\n";
	}
	// Write to table
	$sql = "LOAD DATA LOCAL INFILE '/cygwin64/home/Tyler/Research/TCR/ATLAS/Authors.txt' INTO TABLE Authors;";
	if (mysqli_query($conn, $sql)) {
		echo "Data loaded successfully\n";
	}
	else {
		echo "Error loading data: " . mysqli_error($conn) . "\n";
	}
	// TCRs
	$sql = "CREATE TABLE TCRs(
			TCRname VARCHAR(50) NOT NULL,
			TRAV VARCHAR(50) NOT NULL,
			TRBV VARCHAR(50) NOT NULL,
			wtPDB CHAR(4) NOT NULL,
			PRIMARY KEY (TCRname));";
	if (mysqli_query($conn, $sql)) {
		echo "Table created successfully\n";
	}
	else {
		echo "Error creating table: " . mysqli_error($conn) . "\n";
	}
	// Write to table
	$sql = "LOAD DATA LOCAL INFILE '/cygwin64/home/Tyler/Research/TCR/ATLAS/TCR_table.txt' INTO TABLE TCRs;";
	if (mysqli_query($conn, $sql)) {
		echo "Data loaded successfully\n";
	}
	else {
		echo "Error loading data: " . mysqli_error($conn) . "\n";
	}
	// Mutants
	$sql = "CREATE TABLE Mutants(
			TCRname VARCHAR(100) NOT NULL,
			MHCname VARCHAR(100) NOT NULL,
			Mutant VARCHAR(100) NOT NULL,
			Kd VARCHAR(100) NULL,
			PEPseq VARCHAR(100) NOT NULL,
			wtPDB CHAR(4) NULL,
			mutPDB CHAR(4) NULL,
			CDR VARCHAR(100) NULL,
			wtCDRseq VARCHAR(100) NULL,
			Chain VARCHAR(10) NULL,
			PMID INT(8) NOT NULL,
			ExpMethod VARCHAR(100) NOT NULL,
			PRIMARY KEY (TCRname, MHCname, PEPseq, Mutant)
			);";



			
	if (mysqli_query($conn, $sql)) {
		echo "Table created successfully\n";
	}
	else {
		echo "Error creating table: " . mysqli_error($conn) . "\n";
	}
	// Write to table
	$sql = "LOAD DATA LOCAL INFILE '/cygwin64/home/Tyler/Research/TCR/ATLAS/Mutants.txt' INTO TABLE Mutants;";
	if (mysqli_query($conn, $sql)) {
		echo "Data loaded successfully\n";
	}
	else {
		echo "Error loading data: " . mysqli_error($conn) . "\n";
	}



?>   