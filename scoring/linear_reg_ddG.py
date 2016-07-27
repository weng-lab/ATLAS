#!/usr/bin/env python
import argparse
import linear_reg_functions as lrf
import numpy as np
import statsmodels.api as sm
import matplotlib.pyplot as plt

parser = argparse.ArgumentParser(description='Perform multilinear regression on ATLAS data for ddG values')
parser.add_argument('-in', help='ddG formatted data table (energy_table_ddG.txt)', type=str, dest='infile', required=True)
args = parser.parse_args()


def main():
	######################################################
	# Linear Regression to obtain coefficients and p-vals
	######################################################
	# Open data table
	IN = open(args.infile, 'r')
	# Column indices for energy terms
	feature_cols = [38,41,42,43,44,45,46,47]
	pdb_cols = [17,22]
	num_features = len(feature_cols)
	# Load data and normalize
	ddGs, X, header, pdbs = lrf.load_data_ddG(IN, feature_cols, pdb_cols)

	num_samples=len(ddGs)
	X = lrf.add_constant(X)
	for i in range(1, num_features + 1):
		X[:,i] = lrf.feature_scale(X[:,i])
	
	print '\nStatsmodel'
	res_ols = sm.OLS(ddGs, X).fit()
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
		prediction = lrf.LOCOCV(i, X, ddGs, pdbs)
		predictions.append(prediction)
	predictions = np.array(predictions)

	# Stats
	testErr = lrf.RMSE(predictions, ddGs)
	print '*******************************'
	print 'RMSE: ' + str(testErr) + ' kcal/mol'
	r = np.corrcoef(predictions, ddGs)
	print 'Pearson\'s r: ' + str(r[0,1])
	print '*******************************'
	
	plt.plot(range(-11,11), range(-11,11), c='r')
	plt.xlim(-10,10)
	plt.ylim(-6,6)
	plt.scatter(ddGs, predictions)
	plt.title('ATLAS TCR-pMHC complexes')
	plt.xlabel('Experimentally measured ddG (kcal/mol)')
	plt.ylabel('Predicted ddG (kcal/mol)')
	plt.savefig('prediction_scatterplot_ddG.png')

	














if __name__ == '__main__':
	main()