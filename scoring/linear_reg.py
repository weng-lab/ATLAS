#!/usr/bin/env python

import argparse
import numpy as np
import matplotlib.pyplot as plt
import re
from sklearn import linear_model

parser = argparse.ArgumentParser(description='Perform multilinear regression on ATLAS data with Rosetta energy terms')
parser.add_argument('-in', help='data table output by build_models.py', type=str, dest='infile', required=True)
args = parser.parse_args()


def gibbs_free_energy(Kd):
	'''
	Calculate delta G from micromolar Kd value
	'''
	dG = .001987*298.15*np.log(Kd*10.0**-6)
	return dG



def load_data(fh, start_col, num_features):
	'''
	Load data into design matrix and dependent variable vector for dG values
	'''
	dGs = []
	X = []
	header = fh.readline().split('\t')
	dG_col = header.index('DeltaG_kcal_per_mol')
	Kd_col = header.index('Kd_microM')

	for line in fh:
		# Dependent variable
		split_line=line.split('\t')
		Kd = split_line[Kd_col]
		if Kd == 'n.d.':
			dG = -5.05
		elif '>' in Kd:
			obj = re.search('>([\d\.]+)', Kd)
			dG = gibbs_free_energy(float(obj.group(1)))
		else:
			dG = split_line[dG_col]
			obj = re.search('-?[\d\.]+', dG)
			dG = float(obj.group())
		# Limit affinity to max Kd of 200 mM ~ -5.05 kcal/mol
		if dG > -5.05:
				dG = -5.05
		dGs.append(dG)

		# Design matrix
		X.append(map(float,split_line[start_col:start_col + num_features]))

	dGs = np.array(dGs)
	X = np.array(X)
	return dGs, X

def add_constant(X):
	'''
	Add constant variable
	'''
	const = np.ones(len(X)).reshape(len(X),1)
	return np.hstack((const, X))

def feature_scale(d):
	'''
	Normalize features of design matrix
	'''
	return (d - np.mean(d)) / np.std(d)
	
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
	# Column index for start of energy terms
	start_col=33
	num_features = 7
	# Load data and normalize
	dGs, X =  load_data(IN, start_col, num_features)
	num_samples=len(dGs)
	X = add_constant(X)
	for i in range(1, num_features + 1):
		X[:,i] = feature_scale(X[:,i])

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
	plt.plot(range(-15,1), range(-15,1), c='r')
	plt.xlim(-13,-4)
	plt.ylim(-12,-3)
	plt.scatter(dGs, predicted_values)
	plt.title('ATLAS TCR-pMHC complexes')
	plt.xlabel('Experimentally measured binding affinity (kcal/mol)')
	plt.ylabel('Predicted binding affinity (kcal/mol)')
	plt.savefig('prediction_scatterplot.png')


if __name__ == '__main__':
	main()
