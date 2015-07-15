#!/usr/bin/env python

import argparse
import numpy as np
import matplotlib.pyplot as plt
import re
from sklearn import linear_model

parser = argparse.ArgumentParser(description='Get correlations and plot Rosetta score12 predictions with experimental binding affinities')
parser.add_argument('-in', help='data table output by build_models_score12.py', type=str, dest='infile', required=True)
args = parser.parse_args()


def gibbs_free_energy(Kd):
	'''
	Calculate delta G from micromolar Kd value
	'''
	dG = .001987*298.15*np.log(Kd*10.0**-6)
	return dG



def load_data(fh):
	'''
	Load data 
	'''
	dGs = []
	dG_bind_score12 = []
	score12=[]
	header = fh.readline().strip().split('\t')
	dG_col = header.index('DeltaG_kcal_per_mol')
	Kd_col = header.index('Kd_microM')
	dG_bind_score12_col = header.index('dG_bind_score12')
	score12_col = header.index('score12')


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

		dG_bind_score12.append(float(split_line[dG_bind_score12_col]))
		score12.append(float(split_line[score12_col]))

	dGs = np.array(dGs)
	dG_bind_score12 = np.array(dG_bind_score12)
	score12 = np.array(score12)
	return dGs, dG_bind_score12, score12

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
	
	# Load data and normalize
	dGs, dG_bind_score12, score12 =  load_data(IN)
	

	# Stats
	print '\n\n'
	print '*******************************'
	print 'Stats for dG_bind_score12'
	# testErr = RMSE(dG_bind_score12, dGs)
	print '*******************************'
	# print 'RMSE: ' + str(testErr) + ' kcal/mol'
	r = np.corrcoef(dG_bind_score12, dGs)
	print 'Pearson\'s r: ' + str(r[0,1])
	print '*******************************'
	print '\n\n'
	print '*******************************'
	print 'Stats for score12'
	# testErr = RMSE(score12, dGs)
	print '*******************************'
	# print 'RMSE: ' + str(testErr) + ' kcal/mol'
	r = np.corrcoef(score12, dGs)
	print 'Pearson\'s r: ' + str(r[0,1])
	print '*******************************'

	
	# Plotting
	#plt.plot(range(-15,1), range(-15,1), c='r')
	#plt.xlim(-13,-4)
	#plt.ylim(-12,-3)
	plt.scatter(dGs, dG_bind_score12)
	plt.title('ATLAS TCR-pMHC complexes')
	plt.xlabel('Experimentally measured binding affinity (kcal/mol)')
	plt.ylabel('Rosetta dG bind with score12')
	plt.savefig('dG_bind_score12_prediction_scatterplot.png')
	plt.clf()
	plt.scatter(dGs, score12)
	plt.title('ATLAS TCR-pMHC complexes')
	plt.xlabel('Experimentally measured binding affinity (kcal/mol)')
	plt.ylabel('Rosetta score12')
	plt.savefig('score12_prediction_scatterplot.png')
	plt.clf()



if __name__ == '__main__':
	main()
