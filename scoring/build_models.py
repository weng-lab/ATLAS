#!/usr/bin/env python

import argparse
import pandas as pd
import subprocess

parser = argparse.ArgumentParser(description='build model complexes and score using Rosetta\'s fixbb app')
parser.add_argument('-f', help='ATLAS Mutants tab-delimited file (ex. Mutants_052915.tsv)', type=str, dest='f',required=True)
parser.add_argument('-w', help='Weights file for scoring with Rosetta (ex. weights_1.wts)', type=str, dest='w',required=True)
parser.add_argument('-r', help='path to Rosetta3 (ex. /home/borrmant/Research/TCR/rosetta/rosetta-3.5/)', type=str, dest='ros_path', required=True)
parser.add_argument('-s', help='path to Brian Pierce\'s TCR-pMHC structure database (ex. /home/borrmant/Research/TCR/tcr_structure_database/all/)',
	type=str, dest='struct_path', required=True) 
args = parser.parse_args()



def score(pdb, weights, label):
	'''
	Score PDB complex using Rosetta3 scoring function
	'''
	score_cmd = [args.ros_path + 'rosetta_source/bin/score.linuxgccrelease', '-database',
	args.ros_path + 'rosetta_database/', '-s', args.struct_path +  pdb + '.pdb', '-out:file:scorefile', 
	label + '_score.sc', '-extrachi_cutoff', '1', '-ex1', '-ex2', '-ex3', '-score:weights',
	weights]
	process = subprocess.Popen(score_cmd)
	process.wait()
	
def isolate(pdb, component):
	'''
	Isolate chain components from PDB structure
	'''
	if component == TCR:
		IN = open(args.struct_path + pdb + '.pdb', 'r')







def main():
	# Read Mutants table into dataframe
	df = pd.read_csv(args.f, sep='\t')
	counter = 0
	for i, row in df.iterrows():
		# Check if we need to model entry
		if pd.notnull(row['true_PDB']):
			true_pdb = str(row['true_PDB'])
			# Score TCR-pMHC
			score(true_pdb, args.w, 'com')
			# Score TCR

	print counter












if __name__ == '__main__':
	main()
