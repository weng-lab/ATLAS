#!/usr/bin/env python

import argparse
import numpy as np
from sklearn import linear_model
import statsmodels.api as sm

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

def load_data(fh, col_ind):
	'''
	Load data into design matrix and dependent variable vector for dG values
	'''
	dGs = []
	X = []
	header = fh.readline().split('\t')
	dG_col = header.index('DeltaG_kcal_per_mol')

	for line in fh:
		# Dependent variable
		split_line=np.array(line.split('\t'))
		dG = split_line[dG_col]
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
	return dGs, X

def calculate_thetas(X, y):
	'''
	Calculate theta coefficients by ordinary least squares
	'''
	X = np.mat(X)
	y = y.reshape(len(y),1)
	thetas = (X.T*X).I*X.T*y
	return thetas

def main():
	######################################################
	# Linear Regression to obtain coefficients and p-vals
	######################################################
	# Open data table
	IN = open(args.infile, 'r')
	# Column indices for energy terms
	feature_cols = [37,40,41,42,43,44,45,46]
	num_features = len(feature_cols)
	# Load data and normalize
	dGs, X =  load_data(IN, feature_cols)
	num_samples=len(dGs)
	X = add_constant(X)
	for i in range(1, num_features + 1):
		X[:,i] = feature_scale(X[:,i])
	

	print 'Sklearn'
	lr = linear_model.LinearRegression()
	lr.fit(X,dGs)
	print 'Coef:'
	print lr.coef_
	print 'Int:'
	print lr.intercept_


	print '\nAnalytical'
	thetas = calculate_thetas(X, dGs)
	print 'Coef:'
	print thetas

	print '\nStatsmodel'
	res_ols = sm.OLS(dGs, X).fit()
	print 'Coef:'
	print res_ols.params
	print res_ols.summary()
	print res_ols.pvalues

if __name__ == '__main__':
	main()