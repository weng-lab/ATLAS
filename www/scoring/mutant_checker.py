#!/usr/bin/env python

import pandas as pd 
import argparse

parser=argparse.ArgumentParser(description='Checks and validates mutant information for ATLAS table')
parser.add_argument('-f', help='ATLAS table', type=str, dest='f', required=True)
parser.add_argument('-p', help='Path to true_pdb directory (ex. structures/true_pdb/)', type=str, dest='p', required=True)
args=parser.parse_args()

def mutant_check(mut_str, pdb, p, chain_str=False):
	'''
	Checks that listed mutation has the correct amino acid at residue position in template PDB
	'''
	aa_map = {'A': 'ALA', 'R': 'ARG', 'D' : 'ASP',
			'N': 'ASN', 'C': 'CYS', 'E': 'GLU', 
			'Q': 'GLN', 'G': 'GLY', 'H': 'HIS',
			'I': 'ILE', 'L': 'LEU', 'K': 'LYS',
			'M': 'MET', 'F': 'PHE', 'P': 'PRO',
			'S': 'SER', 'T': 'THR', 'W': 'TRP',
			'Y': 'TYR', 'V': 'VAL'
			}

	muts = [x.strip() for x in mut_str.split('|')] 
	if chain_str:
		chains = [x.strip() for x in chain_str.split('|')]
		if len(muts) != len(chains):
			print 'ERROR: Unequal number of mutations and number of chains'
			return 0
	

	for i in range(len(muts)):
		PDB_f = open(p + '/' + pdb + '.pdb', 'r')
		found_pos = False
		for line in PDB_f:
			splitline = line.split()
			if chain_str:
				if splitline[0] == 'ATOM' and splitline[4] == chains[i] and splitline[5] == muts[i][1:-1]:
					found_pos = True 
					if aa_map[muts[i][0]] == splitline[3]:
						# print 'ok'
						pass
					else:
						print 'ERROR: template residue does not match literature residue'
						return 0
			else:
				if splitline[0] == 'ATOM' and splitline[4] == 'C' and splitline[5] == muts[i][1:-1]:
					found_pos = True 
					if aa_map[muts[i][0]] == splitline[3]:
						# print 'ok'
						pass
					else:
						print 'ERROR: template residue does not match literature residue'
						return 0
		if not found_pos:
			print 'ERROR: could not find chain and residue in pdb'  
			return 0
		PDB_f.close()
	return 1



def table_checker(df, p):
	''' 
	Verifies mutant information from ATLAS dataframe 
	'''
	for i, row in df.iterrows():
		if pd.notnull(row['template_PDB']):
			# Check TCR mutations
			if row['TCR_mut'].strip() != 'WT':
				result = mutant_check(str(row['TCR_mut']).strip(), str(row['template_PDB']).strip(), p, str(row['TCR_PDB_chain']).strip())
				if not result:
					print 'Index: '+str(row['Index']), 'TCR: '+str(row['TCRname']), 'PMID: '+str(row['PMID'])
			# Check MHC mutations
			if row['MHC_mut'].strip() != 'WT':
				result = mutant_check(str(row['MHC_mut']).strip(), str(row['template_PDB']).strip(), p, str(row['MHC_mut_chain']).strip())
				if not result:
					print 'Index: '+str(row['Index']), 'TCR: '+str(row['TCRname']), 'PMID: '+str(row['PMID'])
			# Check peptide mutations
			if row['PEP_mut'].strip() != 'WT':
				result = mutant_check(str(row['PEP_mut']).strip(), str(row['template_PDB']).strip(), p)
				if not result:
					print 'Index: '+str(row['Index']), 'TCR: '+row['TCRname'], 'PMID: '+str(row['PMID'])




def main():
	df = pd. read_csv(args.f, sep='\t')
	table_checker(df, args.p)



if __name__ == '__main__':
	main()