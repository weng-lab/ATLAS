#!/usr/bin/env python


import argparse
import numpy as np
import matplotlib.pyplot as plt
import re
from sklearn import linear_model
import linear_reg_functions as lrf

parser = argparse.ArgumentParser(description='Perform multilinear regression and simple LOOCV on ATLAS data with Rosetta energy terms')
parser.add_argument('-in', help='energy_table.txt', type=str, dest='infile', required=True)
args = parser.parse_args()

def plot_experiment_vs_predict(dGs, predictions, r):
	

	fig, ax = plt.subplots(figsize=(3.7,3.2))
	ax.plot(range(-15,1), range(-15,1), c='r', linewidth=2)
	ax.spines['right'].set_visible(False)
	ax.spines['top'].set_visible(False)
	ax.spines['left'].set_linewidth(2)
	ax.spines['bottom'].set_linewidth(2)
	ax.yaxis.set_ticks_position('left')
	ax.xaxis.set_ticks_position('bottom')
	ax.xaxis.set_tick_params(width=2)
	ax.yaxis.set_tick_params(width=2)
	ax.set_xlim([-16,-4])
	ax.set_ylim([-12,-2])
	ax.scatter(dGs, predictions, s=8)
	xticks = map(int,ax.get_xticks().tolist())
	yticks = map(int, ax.get_yticks().tolist())
	ax.set_xticklabels(xticks, fontsize=9)
	ax.set_yticklabels(yticks, fontsize=9)
	ax.text(-14, -4, 'r = ' + str(round(r[0,1], 2)), fontsize=14)
	ax.set_xlabel(r'Experimentally measured $\Delta$G (kcal/mol)', fontsize=10)
	ax.set_ylabel(r'Predicted $\Delta$G (kcal/mol)', fontsize=10)
	plt.tight_layout()
	plt.savefig('LOOCV.png', dpi=300)
	plt.close()
	return


def calculate_thetas(X, y):
	'''
	Calculate theta coefficients by ordinary least squares
	'''
	X = np.mat(X)
	y = y.reshape(len(y),1)
	thetas = (X.T*X).I*X.T*y
	return thetas


def RMSE(pred, true):
	'''
	Calculate root mean square error
	'''
	rmse = np.sqrt(np.mean(np.square(pred-true)))
	return rmse



def main():
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

	# Leave-one-out cross-validation
	predicted_values = np.zeros(num_samples)
	weight_matrix = np.zeros((num_samples, num_features+1))
	rowIDs = range(num_samples)
	for rowID in range(num_samples):
		rowIDs.remove(rowID)
		x = X[rowIDs, :] # extract just data needed
		thetas = calculate_thetas(x, dGs[rowIDs])
		x = np.mat(X[rowID, :]) # test on sample we left out
		predicted_values[rowID] = x*thetas
		weight_matrix[rowID,:] = thetas.T
		rowIDs.append(rowID) # put it back in for next round

	# Stats
	testErr = RMSE(predicted_values, dGs)
	print '*******************************'
	print 'RMSE: ' + str(testErr) + ' kcal/mol'
	r = np.corrcoef(predicted_values, dGs)
	print 'Pearson\'s r: ' + str(r[0,1])
	print '*******************************'
	
	print np.mean(weight_matrix, axis = 0)
	plot_experiment_vs_predict(dGs, predicted_values, r)


	# plt.plot(range(-15,1), range(-15,1), c='r')
	# plt.xlim(-13,-4)
	# plt.ylim(-12,-3)
	# plt.scatter(dGs, predicted_values)
	# plt.title('ATLAS TCR-pMHC complexes')
	# plt.xlabel('Experimentally measured binding affinity (kcal/mol)')
	# plt.ylabel('Predicted binding affinity (kcal/mol)')
	# plt.savefig('LOOCV.png')


if __name__ == '__main__':
	main()