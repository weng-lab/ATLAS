#!/usr/bin/env python

import argparse
import numpy as np
import statsmodels.api as sm
import matplotlib.pyplot as plt

parser = argparse.ArgumentParser(description='Perform multilinear regression on ATLAS data with Rosetta energy terms')
parser.add_argument('-in', help='data table output by energy_table.py', type=str, dest='infile', required=True)
args = parser.parse_args()

def add_constant(X):
	'''
	Add constant variable
	'''
	const = np.ones(len(X)).reshape(len(X),1)
	return np.hstack((const, X))


def is_float(s):
    try:
        float(s)
        return True
    except ValueError:
        return False

def feature_scale(d):
	'''
	Normalize features of design matrix
	'''
	return (d - np.mean(d)) / np.std(d)

def load_data(fh, col_ind, pdb_ind):
	'''
	Load data into design matrix and dependent variable vector for dG values
	'''
	dGs = []
	X = []
	pdbs = []
	header = fh.readline().split('\t')
	dG_col = header.index('DeltaG_kcal_per_mol')

	for line in fh:
		# Dependent variable
		split_line=np.array(line.split('\t'))
		dG = split_line[dG_col]
		# PDBs
		pdb = split_line[pdb_ind]
		pdbs.append(pdb)

		# Limit affinity to max Kd of 200 mM ~ -5.05 kcal/mol
		if is_float(dG):
			if float(dG) > -5.05:
				dG = -5.05
			else:
				dG = float(dG)
		else:
			dG = -5.05
		dGs.append(dG)

		# Design matrix
		X.append(map(float,split_line[col_ind]))
	
	dGs = np.array(dGs)
	X = np.array(X)
	pdbs = np.array(pdbs)
	header = np.array(header)[np.concatenate((col_ind, pdb_ind))]

	return dGs, X, header, pdbs

def LOCOCV(i, X, dGs, pdbs):
	'''Leave one complex out cross-validation (LOCOCV)'''
	energies = X[i,:]
	pdb = pdbs[i,:]
	true_pdb = pdb[0]
	temp_pdb = pdb[1]
	
	if true_pdb != '':
		
		train = X[np.all(pdbs != true_pdb, axis=1), :]
		y = dGs[np.all(pdbs!= true_pdb, axis=1)]
		res_ols = sm.OLS(y, train).fit()
		prediction = res_ols.predict(energies)
		print res_ols.params
		return float(prediction)
		

	elif temp_pdb != '':
		train = X[np.all(pdbs != temp_pdb, axis=1), :]
		y = dGs[np.all(pdbs!= temp_pdb, axis=1)]
		res_ols = sm.OLS(y, train).fit()

		prediction = res_ols.predict(energies)
		print res_ols.params
		return float(prediction)

	else:
		print 'ERROR: PDB info missing'
		quit()


def RMSE(pred, true):
	'''
	Calculate root mean square error
	'''
	rmse = np.sqrt(np.mean(np.square(pred-true)))
	return rmse


def main():
	######################################################
	# Linear Regression to obtain coefficients and p-vals
	######################################################
	# Open data table
	IN = open(args.infile, 'r')
	# Column indices for energy terms
	feature_cols = [37,40,41,42,43,44,45,46]
	pdb_cols = [16, 21]
	num_features = len(feature_cols)
	# Load data and normalize
	dGs, X, header, pdbs =  load_data(IN, feature_cols, pdb_cols)
	num_samples=len(dGs)
	X = add_constant(X)
	for i in range(1, num_features + 1):
		X[:,i] = feature_scale(X[:,i])
	
	print '\nStatsmodel'
	res_ols = sm.OLS(dGs, X).fit()
	print 'Coef:'
	print res_ols.params
	print res_ols.summary()
	print res_ols.pvalues

	print '\nHeader'
	print header

	# Leave one complex out cross-validation (LOCOCV)
	predictions = []
	for i in range(len(X)):
		prediction = LOCOCV(i, X, dGs, pdbs)
		predictions.append(prediction)
	predictions = np.array(predictions)

	# Stats
	testErr = RMSE(predictions, dGs)
	print '*******************************'
	print 'RMSE: ' + str(testErr) + ' kcal/mol'
	r = np.corrcoef(predictions, dGs)
	print 'Pearson\'s r: ' + str(r[0,1])
	print '*******************************'
	
	plt.plot(range(-15,1), range(-15,1), c='r')
	plt.xlim(-15,-4)
	plt.ylim(-12,-2)
	plt.scatter(dGs, predictions)
	plt.title('ATLAS TCR-pMHC complexes')
	plt.xlabel('Experimentally measured binding affinity (kcal/mol)')
	plt.ylabel('Predicted binding affinity (kcal/mol)')
	plt.savefig('prediction_scatterplot.png')

	


if __name__ == '__main__':
	main()