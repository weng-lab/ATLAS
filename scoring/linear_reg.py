#!/usr/bin/env python

import argparse
import linear_reg_functions as lrf
import numpy as np
import statsmodels.api as sm
import matplotlib.pyplot as plt

parser = argparse.ArgumentParser(description='Perform multilinear regression on ATLAS data with Rosetta energy terms')
parser.add_argument('-in', help='data table output by energy_table.py', type=str, dest='infile', required=True)
args = parser.parse_args()


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
	dGs, X, header, pdbs =  lrf.load_data(IN, feature_cols, pdb_cols)
	num_samples=len(dGs)
	X = lrf.add_constant(X)
	for i in range(1, num_features + 1):
		X[:,i] = lrf.feature_scale(X[:,i])
	
	print '\nStatsmodel'
	res_ols = sm.OLS(dGs, X).fit()
	print 'Coef:'
	print res_ols.params
	print res_ols.summary()
	print 'Pvals:'
	print res_ols.pvalues

	print '\nHeader'
	print header

	# Leave one complex out cross-validation (LOCOCV)
	predictions = []
	for i in range(len(X)):
		prediction = lrf.LOCOCV(i, X, dGs, pdbs)
		predictions.append(prediction)
	predictions = np.array(predictions)

	# Stats
	testErr = lrf.RMSE(predictions, dGs)
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