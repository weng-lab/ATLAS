#!/usr/bin/env python

import argparse
import os
import pandas as pd
import numpy as np
import subprocess
import re

parser = argparse.ArgumentParser(description='score model complexes using Rosetta\'s score app')
parser.add_argument('-f', help='ATLAS Mutants tab-delimited file (ex. Mutants_052016.txt)', type=str, dest='f',required=True)
parser.add_argument('-w', help='Weights file for scoring with Rosetta (ex. weights_1.wts)', type=str, dest='w',required=True)
parser.add_argument('-r', help='path to Rosetta3 (ex. /home/borrmant/Research/TCR/rosetta/rosetta-3.5/)', type=str, dest='ros_path', required=True)
parser.add_argument('-s', help='path to Brian Pierce\'s TCR-pMHC structure database (ex. /home/borrmant/Research/TCR/tcr_structure_database/all/)',
	type=str, dest='struct_path', required=True) 
parser.add_argument('-b', help='use backrub models (ex. True)', type=bool, default=False)
args = parser.parse_args()

def score(pdb, weights, label):
	'''
	Score PDB complex using Rosetta3 scoring function
	'''
	score_cmd = [args.ros_path + 'rosetta_source/bin/score.linuxgccrelease', '-database',
	args.ros_path + 'rosetta_database/', '-s', pdb , '-out:file:scorefile', 
	label + '_score.sc', '-extrachi_cutoff', '1', '-ex1', '-ex2', '-ex3', '-score:weights',
	weights]
	process = subprocess.Popen(score_cmd)
	process.wait()
	
def isolate(pdb, component):
	'''
	Isolate chain components from PDB structure
	'''
	if component == 'TCR':
		IN = open(pdb, 'r')
		OUT = open(component + '.pdb', 'w')
		for line in IN:
			row = line.split()
			if row[0] == 'ATOM':
				if row[4] == 'D' or row[4] == 'E':
					OUT.write(line)
		IN.close()
		OUT.close()
	elif component == 'pMHC':
		IN = open(pdb, 'r')
		OUT = open(component + '.pdb', 'w')
		for line in IN:
			row = line.split()
			if row[0] == 'ATOM':
				if row[4] == 'A' or row[4] == 'B' or row[4] == 'C':
					OUT.write(line)
		IN.close()
		OUT.close()
	else:
		print 'ERORR: unidentified component name'
		quit()

def dG_bind(COM_sc, TCR_sc, pMHC_sc):
	'''
	Calculate dG_bind scores = COM_score - (TCR_score + pMHC_score)
	'''
	COM = open(COM_sc, 'r')
	TCR = open(TCR_sc, 'r')
	pMHC= open(pMHC_sc, 'r')

	header = COM.readline().split()[2:21]
	TCR.readline()
	pMHC.readline()
	COM_scores = np.array(map(float, COM.readline().split()[2:21]))
	TCR_scores = np.array(map(float, TCR.readline().split()[2:21]))
	pMHC_scores = np.array(map(float, pMHC.readline().split()[2:21]))
	dG_scores = COM_scores - (TCR_scores + pMHC_scores)
	dG_hash = {}
	for i, energy in enumerate(header):
		dG_hash[energy] = dG_scores[i]
	COM.close()
	TCR.close()
	pMHC.close()
	return dG_hash

def main():
	# Read Mutants table into dataframe
	df = pd.read_csv(args.f, sep='\t')
	# Add energy term columns
	WF = open(args.w, 'r')
	for line in WF:
		energy = line.split()[0]
		df[energy] = np.nan
	for i, row in df.iterrows():
		# Check if we need to use designed structure
		if pd.notnull(row['true_PDB']):
			true_pdb = str(row['true_PDB'])
			# Score TCR-pMHC
			score(args.struct_path + 'true_pdb/' + true_pdb + '.pdb', args.w, 'COM')
			# Score TCR
			isolate(args.struct_path + 'true_pdb/' + true_pdb + '.pdb', 'TCR')
			score('TCR.pdb', args.w, 'TCR')
			# Score pMHC
			isolate(args.struct_path + 'true_pdb/' +  true_pdb + '.pdb', 'pMHC')
			score('pMHC.pdb', args.w, 'pMHC')			
			# Calculate dG bind
			dG_scores = dG_bind('COM_score.sc', 'TCR_score.sc', 'pMHC_score.sc')
			# Append to dataframe
			for energy in dG_scores:
				df.loc[i, energy] = dG_scores[energy]
			# Remove temporary files
			os.remove('COM_score.sc')
			os.remove('TCR_score.sc')
			os.remove('pMHC_score.sc')
			os.remove('TCR.pdb')
			os.remove('pMHC.pdb')
		
		# Use designed structure
		elif pd.isnull(row['true_PDB']):
			# Use backrub models?
			if args.b:
				designed_pdb_folder = 'backrub_pdb/'
			else:
				designed_pdb_folder = 'designed_pdb/'

			# Get designed PDB file
			template_PDB = str(row['template_PDB'])
			MHC_mut = ''.join(str(row['MHC_mut']).replace('|', '.').split())
			if 'A' not in str(row['MHC_mut_chain']) and 'B' not in str(row['MHC_mut_chain']):
				MHC_mut_chain = 'nan'
			else:
				MHC_mut_chain = ''.join(str(row['MHC_mut_chain']).replace('|', '.').split()) 

			TCR_mut = ''.join(str(row['TCR_mut']).replace('|', '.').split())
			if 'A' not in str(row['TCR_mut_chain']) and 'B' not in str(row['TCR_mut_chain']):
				TCR_mut_chain = 'nan'
			else:
				TCR_mut_chain = ''.join(str(row['TCR_mut_chain']).replace('|', '.').split())
			PEP_mut =  ''.join(str(row['PEP_mut']).replace('|', '.').split())
			PDB_f = '_'.join([template_PDB, MHC_mut, MHC_mut_chain, TCR_mut, TCR_mut_chain, PEP_mut])
			# Score TCR-pMHC
			score(args.struct_path + designed_pdb_folder + PDB_f + '.pdb', args.w, 'COM')
			# Score TCR
			isolate(args.struct_path + designed_pdb_folder + PDB_f + '.pdb', 'TCR')
			score('TCR.pdb', args.w, 'TCR')
			# Score pMHC
			isolate(args.struct_path + designed_pdb_folder +  PDB_f + '.pdb', 'pMHC')
			score('pMHC.pdb', args.w, 'pMHC')			
			# Calculate dG bind
			dG_scores = dG_bind('COM_score.sc', 'TCR_score.sc', 'pMHC_score.sc')
			# Append to dataframe
			for energy in dG_scores:
				df.loc[i, energy] = dG_scores[energy]
			# Remove temporary files
			os.remove('COM_score.sc')
			os.remove('TCR_score.sc')
			os.remove('pMHC_score.sc')
			os.remove('TCR.pdb')
			os.remove('pMHC.pdb')
	# Write to file
	if args.b:
		df.to_csv('energy_table_backrub.txt', sep='\t', index=False)
	else:
		df.to_csv('energy_table.txt', sep='\t', index=False)



if __name__ == '__main__':
	main()