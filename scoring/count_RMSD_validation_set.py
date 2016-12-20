#!/usr/bin/env python
import argparse
import pandas as pd

parser = argparse.ArgumentParser(description='Count ATLAS entries with true and template PDBs')
parser.add_argument('-f', help='ATLAS Mutants tab-delimited file (ex. Mutants_052016.txt)', type=str, dest='f',required=True)
args=parser.parse_args()

def main():
	counter = 0
	# Read Mutants table into dataframe
	df = pd.read_csv(args.f, sep='\t')
	OUT = open('count_RMSD_validation_set.out', 'w')
	OUT.write('\t'.join(['TCR_mut', 'TCR_mut_chain', 'MHC_mut', 'MHC_mut_chain', 'PEP_mut', 
	'true_PDB', 'template_PDB'])+ '\n')
	# Add energy term columns
	for i, row in df.iterrows():
		if pd.notnull(row['true_PDB']) and pd.notnull(row['template_PDB']):
			counter += 1
			OUT.write('\t'.join([str(row['TCR_mut']), str(row['TCR_mut_chain']),
			str(row['MHC_mut']), str(row['MHC_mut_chain']), str(row['PEP_mut']), str(row['true_PDB']), 
			str(row['template_PDB'])]) + '\n')
	OUT.close()

	print str(counter) + ' ATLAS entries with true and template PDBs'


if __name__ == '__main__':
	main()