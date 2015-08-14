#!/usr/bin/env python

import argparse
import os
import pandas as pd
import numpy as np
import subprocess
import re

parser = argparse.ArgumentParser(description='build model complexes and score using Rosetta\'s fixbb app and default score12 function')
parser.add_argument('-f', help='ATLAS Mutants tab-delimited file (ex. Mutants_052915.tsv)', type=str, dest='f',required=True)
parser.add_argument('-r', help='path to Rosetta3 (ex. /home/borrmant/Research/TCR/rosetta/rosetta-3.5/)', type=str, dest='ros_path', required=True)
parser.add_argument('-s', help='path to Brian Pierce\'s TCR-pMHC structure database (ex. /home/borrmant/Research/TCR/tcr_structure_database/all/)',
	type=str, dest='struct_path', required=True) 
args = parser.parse_args()



def score(pdb, label):
	'''
	Score PDB complex using Rosetta3 scoring function
	'''
	score_cmd = [args.ros_path + 'rosetta_source/bin/score.linuxgccrelease', '-database',
	args.ros_path + 'rosetta_database/', '-s', pdb , '-out:file:scorefile', 
	label + '_score.sc']
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

	
	COM.readline()
	TCR.readline()
	pMHC.readline()
	COM_score = float(COM.readline().split()[1])
	TCR_score = float(TCR.readline().split()[1])
	pMHC_score = float(pMHC.readline().split()[1])
	dG_score = COM_score - (TCR_score + pMHC_score)
	COM.close()
	TCR.close()
	pMHC.close()
	return dG_score, COM_score

def main():
	# Read Mutants table into dataframe
	df = pd.read_csv(args.f, sep='\t')
	# Add score term columns
	df['dG_bind_score12'] = np.nan
	df['score12'] = np.nan

	for i, row in df.iterrows():
		# Check if we need to model entry
		if pd.notnull(row['true_PDB']):
			true_pdb = str(row['true_PDB'])
			# Score TCR-pMHC
			score(args.struct_path +  true_pdb + '.pdb', 'COM')
			# Score TCR
			isolate(args.struct_path + true_pdb + '.pdb', 'TCR')
			score('TCR.pdb', 'TCR')
			# Score pMHC
			isolate(args.struct_path + true_pdb + '.pdb', 'pMHC')
			score('pMHC.pdb', 'pMHC')
			# Calculate dG bind
			dG_bind_score12, score12 = dG_bind('COM_score.sc', 'TCR_score.sc', 'pMHC_score.sc')
			# Append to table
			df.loc[i, 'dG_bind_score12'] = dG_bind_score12
			df.loc[i, 'score12'] = score12
			# Remove temporary files
			os.remove('COM_score.sc')
			os.remove('TCR_score.sc')
			os.remove('pMHC_score.sc')
			os.remove('TCR.pdb')
			os.remove('pMHC.pdb')
	
		# Design model TCR-pMHC
		elif pd.isnull(row['true_PDB']):
			template_pdb = str(row['template_PDB'])
			# Get mutations that need to be designed
			MHC_mut = df.loc[i, 'MHC_mut']
			MHC_mut_chain = df.loc[i, 'MHC_mut_chain']
			TCR_mut = df.loc[i, 'TCR_mut']
			TCR_mut_chain = df.loc[i, 'TCR_mut_chain']
			PEP_mut = df.loc[i, 'PEP_mut']
			# Make label for structure
			label = '_'.join(map(str,[MHC_mut, MHC_mut_chain, TCR_mut, TCR_mut_chain, PEP_mut]))
			label = '_'+ re.sub('\s+','',label)
			# Score TCR-pMHC
			score('design_structures/' + template_pdb + label + '_0001.pdb', 'COM')
			# Score TCR
			isolate('design_structures/' + template_pdb + label + '_0001.pdb', 'TCR')
			score('TCR.pdb', 'TCR')
			# Score pMHC
			isolate('design_structures/' + template_pdb + label + '_0001.pdb', 'pMHC')
			score('pMHC.pdb', 'pMHC')
			# Calculate dG bind
			dG_bind_score12, score12 = dG_bind('COM_score.sc', 'TCR_score.sc', 'pMHC_score.sc')
			# Append to table
			df.loc[i, 'dG_bind_score12'] = dG_bind_score12
			df.loc[i, 'score12'] = score12
			# Remove temporary files
			os.remove('COM_score.sc')
			os.remove('TCR_score.sc')
			os.remove('pMHC_score.sc')
			os.remove('TCR.pdb')
			os.remove('pMHC.pdb')
			

	# Write to file
	df.to_csv('score12_table.txt', sep='\t', index=False)


if __name__ == '__main__':
	main()
