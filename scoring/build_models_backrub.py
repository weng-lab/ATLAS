#!/usr/bin/env python

import argparse
import os
import pandas as pd
import numpy as np 
import subprocess
import re
import proteindatabank

parser = argparse.ArgumentParser(description='build model complexes using Rosetta\'s backrub app')
parser.add_argument('-f', help='ATLAS Mutants tab-delimited file (ex. Mutants_052016.txt)', type=str, dest='f',required=True)
parser.add_argument('-r', help='path to Rosetta3 (ex. /home/borrmant/Research/TCR/rosetta/rosetta-3.5/)', type=str, dest='ros_path', required=True)
parser.add_argument('-s', help='path to Brian Pierce\'s TCR-pMHC structure database (ex. /home/tb37w/ATLAS/structures/)',
	type=str, dest='struct_path', required=True) 
parser.add_argument('-c', help='Brian Pierce\'s CDR sequences data table (CDR_seqs.txt)', type=str, dest='cdr_seqs', required=True) 
args = parser.parse_args()


def get_pivot_residues(pdb_file, cdr_seqs):
	'''
	Parse pdb_file and cdr_seqs to return the 
	CDR pivot residues (absolute residue numbers)
	for input into backrub app
	'''
	myPDB = proteindatabank.PDB(pdb_file)
	chain_sequence = myPDB.chain_sequence_list()
	all_residues = ''
	for tup in chain_sequence:
		all_residues = all_residues + tup[1]
	cdr_df = pd.read_csv(cdr_seqs, sep='\t')
	# Get CDR sequences
	row =  cdr_df[cdr_df['PDB ID'] == myPDB.ID].iloc[:,5:11]
	# Check for no match and multiple matches
	for cdr in row.values[0]:
		re_results = re.findall('(?=('+cdr+'))', all_residues)
		if len(re_results) == 0:
			print 'ERROR: CDR sequence not found'
			print cdr
			quit()
		if len(re_results) > 1:
			print 'ERROR: multiple CDR sequences found'
			quit()
	# Get list of pivot residue positions for all CDRs
	pivot_positions = []
	for cdr in row.values[0]:
		srch_obj = re.search(cdr, all_residues)
		pivot_positions = pivot_positions + range(srch_obj.start(), srch_obj.end())

	return pivot_positions
def backrub(pdb, label, pivot_residues):
	'''
	Design mutations using Rosetta's backrub application
	'''
	if not os.path.exists('../structures/backrub_pdb'):
		os.makedirs('../structures/backrub_pdb')
	backrub_cmd = ['bsub', '-q', 'short', '-W', '60', '-R', 'rusage[mem=5000]', '-o', label + '.out', '-e', label + '.err',
	args.ros_path + 'rosetta_source/bin/backrub.linuxgccrelease', '-database', 
	args.ros_path + 'rosetta_database/', '-s', pdb, '-backrub:ntrials', '10000', 
	'-pivot_residues'] + map(str, pivot_residues) + [ '-extrachi_cutoff', 
	'1','-ex1', '-ex2', '-ex3', '-overwrite', '-out:path:pdb', 
	'../structures/backrub_pdb']
	process = subprocess.Popen(backrub_cmd)

def main():
	# Read Mutants table into dataframe
	df = pd.read_csv(args.f, sep='\t')

	for i, row in df.iterrows():
		# Get designed structures
		if pd.isnull(row['true_PDB']):
			template_pdb = str(row['template_PDB'])
			# Get mutations of designed structures
			MHC_mut = df.loc[i, 'MHC_mut']
			MHC_mut_chain = df.loc[i, 'MHC_mut_chain']
			TCR_mut = df.loc[i, 'TCR_mut']
			TCR_mut_chain = df.loc[i, 'TCR_mut_chain']
			PEP_mut = df.loc[i, 'PEP_mut']
			# Make label for structure
			label = '_'.join(map(str,[MHC_mut, MHC_mut_chain, TCR_mut, TCR_mut_chain, PEP_mut]))
			label = '_'+ re.sub('\s+','',label)
			label = re.sub('\|', '.', label)

			# Get pivot_residues for CDR loops in absolute residue numbers 
			# absolute residue numbering : 1 to total residues in PDB file
			pivot_residues = get_pivot_residues(args.struct_path + 'true_pdb/' + template_pdb + '.pdb', args.cdr_seqs)

			# Design mutations by backrub app and save structure
			backrub(args.struct_path + 'designed_pdb/' +  template_pdb + label +  '.pdb', label, pivot_residues)


if __name__ == '__main__':
	main()