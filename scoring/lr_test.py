#!/usr/bin/env python

import numpy as np
from sklearn import linear_model
import statsmodels.api as sm
import matplotlib.pyplot as plt


def computeThetaAnalytically(m, y):
    return np.dot(np.dot(np.linalg.inv(np.dot(m.transpose(), m)), m.T), y)

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

def is_float(s):
    try:
        float(s)
        return True
    except ValueError:
        return False


x = np.random.rand(459,20)
col = np.ones((459,1))
x = np.hstack((col,x))
# print np.shape(x)

print 'Sklearn'
y = np.random.rand(459)
lr = linear_model.LinearRegression()
lr.fit(x,y)
print 'Coef:'
print lr.coef_
print 'Int:'
print lr.intercept_

new_x = np.concatenate([[1] ,np.random.rand(20)])
print 'Prediction:'
print lr.predict(new_x)

print '\nAnalytical'
thetas = computeThetaAnalytically(x, y).T
print 'Coef:'
print thetas
print 'Prediction:'
print np.dot(new_x, thetas)

print '\nStatsmodel'
res_ols = sm.OLS(y, x).fit()
print 'Coef:'
print res_ols.params
print 'Prediction:'
print res_ols.predict(new_x)

# Testing solvation
print '\nTesting Solvation'
######################################################
# Linear Regression to obtain coefficients and p-vals
######################################################
# Open data table
IN = open('energy_table.txt', 'r')
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
X = X[:,[0,4]]
print X
print '\n Corr'
print np.corrcoef(X[:,1], dGs)
print dGs
print '\nStatsmodel'
res_ols = sm.OLS(dGs, X).fit()
print 'Coef:'
print res_ols.params
print res_ols.summary()
print 'Pvals:'
print res_ols.pvalues

print '\nHeader'
print header
x_vec = np.array(range(-3, 5))
y_vec = x_vec*-0.18 -6.64
print x_vec
print y_vec

plt.plot(x_vec, y_vec, c='r')
plt.scatter(X[:,1], dGs)
plt.savefig('lr_test.png')
