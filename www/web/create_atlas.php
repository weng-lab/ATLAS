#!/usr/bin/env php
<?php
	require 'ATLAS_functions.php';             
 	$conn = database_connect(); 

 	//Path to data
 	$data_path = '/cygwin64/home/Tyler/Research/ATLAS/';


	// MHCs
	$sql = "CREATE TABLE IF NOT EXISTS MHCs(
			MHCname VARCHAR(20) NOT NULL,
			class VARCHAR(2) NOT NULL,
			PRIMARY KEY (MHCname));";
	if (mysqli_query($conn, $sql)) {
		echo "Table created successfully\n";
	}
	else {
		echo "Error creating table: " . mysqli_error($conn) . "\n";
	}

	// Write to table
	load_data($data_path . "MHCs.txt", "MHCs", $conn);

	// Authors
	$sql = "CREATE TABLE IF NOT EXISTS Authors(
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
	load_data($data_path . "Authors.txt", "Authors", $conn);

	// TCRs
	$sql = "CREATE TABLE IF NOT EXISTS TCRs(
			TCRname VARCHAR(50) NOT NULL,
			TRAV VARCHAR(50) NOT NULL,
			TRBV VARCHAR(50) NOT NULL,
			PRIMARY KEY (TCRname));";
	if (mysqli_query($conn, $sql)) {
		echo "Table created successfully\n";
	}
	else {
		echo "Error creating table: " . mysqli_error($conn) . "\n";
	}
	load_data($data_path . "TCRs.txt", "TCRs", $conn);

	// Mutants
	

	$sql = "CREATE TABLE IF NOT EXISTS Mutants(
			Ind INT NOT NULL,
			TCRname VARCHAR(100) NOT NULL,
			MHCname VARCHAR(100) NOT NULL,
			MHCname_PDB VARCHAR(100) NOT NULL,
			MHC_mut VARCHAR(500) NOT NULL,
			MHC_mut_chain VARCHAR(100) NULL,
			TCR_mut VARCHAR(500) NOT NULL,
			Kd_microM VARCHAR(100) NOT NULL,
			Kon_per_M_per_s FLOAT NULL,
			Koff_per_s FLOAT NULL,
			Kd_wt_div_KD_mut FLOAT NULL,
			DeltaG_kcal_per_mol FLOAT NULL,
			Delta_DeltaG_kcal_per_mol FLOAT NULL,
			Temperature_K FLOAT NULL,
			PEPseq VARCHAR(100) NOT NULL,
			PEP_mut VARCHAR(100) NOT NULL,
			true_PDB VARCHAR(4) NULL,
			Structure_Method VARCHAR(100) NULL,
			Resolution FLOAT NULL,
			R_value FLOAT NULL,
			R_free FLOAT NULL,
			template_PDB VARCHAR(4) NULL,
			pMHC_PDB VARCHAR(4) NULL,
			TCR_mut_chain VARCHAR(100) NULL,
			CDR VARCHAR(100) NULL,
			wtCDRseq VARCHAR(500) NULL,
			TCR_PDB_chain VARCHAR(100) NULL,
			PMID INT(8) NOT NULL,
			Exp_Method VARCHAR(100) NULL,
			SPR_SensorChip VARCHAR(100) NULL,
			Immobilized_Ligand VARCHAR(500) NULL,
			Coupling_Method VARCHAR(100) NULL,
			Analyte VARCHAR(500) NULL, 
			PRIMARY KEY (Ind)
			);";
	
	if (mysqli_query($conn, $sql)) {
		echo "Table created successfully\n";
	}
	else {
		echo "Error creating table: " . mysqli_error($conn) . "\n";
	}
	load_data($data_path . "Mutants.txt", "Mutants", $conn);
