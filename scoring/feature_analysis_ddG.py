#!/usr/bin/env python

import argparse
import linear_reg_functions as lrf
import numpy as np
import statsmodels.api as sm 
import matplotlib.pyplot as plt

parser = argparse.ArgumentParser(description='Run LOCOCV on 2^8 feature combinations and record correlations between predicted'+
	' and experimentally determined affinity')
parser.add_argument('-in', help='ddG formatted data table (energy_table_ddG.txt)', type=str, dest='infile', required=True)
args = parser.parse_args()

def plot_experiment_vs_predict(ddGs, predictions, subset_features):
	plt.figure()
	plt.rc('axes', linewidth=2)
	plt.plot(range(-11,11), range(-11,11), c='r', linewidth=2)
	plt.xlim(-10,10)
	plt.ylim(-6,6)
	plt.scatter(ddGs, predictions)
	#plt.title('ATLAS TCR-pMHC complexes')

	ax = plt.gca()
	for tick in ax.xaxis.get_major_ticks():
	    tick.label1.set_fontsize(14)
	for tick in ax.yaxis.get_major_ticks():
	    tick.label1.set_fontsize(14)

	plt.xlabel(r'Experimentally measured $\Delta\Delta$G (kcal/mol)', fontsize=16)
	plt.ylabel(r'Predicted $\Delta\Delta$G (kcal/mol)', fontsize=16)
	plt.savefig('subset_plots_ddG/' + subset_features + '.png')
	plt.close()
	return


def main():
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

	# Get powerset of 8 features
	ps = lrf.powerset(range(1,9))
	format_header = np.insert(header[:-2], 0,'1')
	ps_header = []
	for subset in ps[1:]:
		ps_header.append(format_header[subset])
	# Perform LOCOCV on each subset of features and record correlation between predicted and experimental affinity
	OUT = open('feature_analysis_ddG.txt', 'w')
	OUT.write('subset\tRMSE (kcal\mol)\tr\n')
	for index, subset in enumerate(ps[1:]):
		subset_X = X[:, [0] + subset]
		# Leave one complex out cross-validation (LOCOCV)
		predictions = []
		for i in range(len(subset_X)):
			prediction = lrf.LOCOCV(i, subset_X, ddGs, pdbs)
			predictions.append(prediction)
		predictions = np.array(predictions)
		OUT.write(' '.join(ps_header[index]) + '\t')
		# Stats
		testErr = lrf.RMSE(predictions, ddGs)
		OUT.write(str(testErr) + '\t')
		r = np.corrcoef(predictions, ddGs)
		OUT.write(str(r[0,1])+'\n')
		# Plot
		plot_experiment_vs_predict(ddGs, predictions, '_'.join(ps_header[index]))
	OUT.close()

if __name__ == '__main__':
		main()