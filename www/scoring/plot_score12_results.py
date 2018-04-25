#!/usr/bin/env python

import argparse
import numpy as np
import matplotlib.pyplot as plt
from matplotlib import lines
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
	TCR_name = []
	header = fh.readline().strip().split('\t')
	dG_col = header.index('DeltaG_kcal_per_mol')
	Kd_col = header.index('Kd_microM')
	dG_bind_score12_col = header.index('dG_bind_score12')
	score12_col = header.index('score12')
	name_col = header.index('TCRname')


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
		TCR_name.append(split_line[name_col])

	dGs = np.array(dGs)
	dG_bind_score12 = np.array(dG_bind_score12)
	score12 = np.array(score12)
	return dGs, dG_bind_score12, score12, TCR_name

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
	dGs, dG_bind_score12, score12, TCR_name =  load_data(IN)
	

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
	A6_ind = [i for i, x in enumerate(TCR_name) if x == 'A6']
	rem_ind = [i for i, x in enumerate(TCR_name) if x != 'A6']
	plt.scatter(dGs[A6_ind], score12[A6_ind], c='r')
	plt.scatter(dGs[rem_ind], score12[rem_ind], c='b')
	my_labels=('A6 TCR', 'Other')
	markers = ['o', 'o', 'o']
	colors = ['r', 'b']
	my_handles = []
	for i in range(len(my_labels)):
		my_handles.append(lines.Line2D([],[], marker=markers[i], markerfacecolor=colors[i], linestyle='None', 
			markeredgecolor='k',markersize=7,markeredgewidth=1,label=my_labels[i]))
	plt.legend(tuple(my_handles), tuple(my_labels), ncol=2, loc='upper center', numpoints=1)
	plt.title('ATLAS TCR-pMHC complexes')
	plt.xlabel('Experimentally measured binding affinity (kcal/mol)')
	plt.ylabel('Rosetta score12')
	plt.savefig('score12_prediction_scatterplot.png')
	plt.clf()



if __name__ == '__main__':
	main()
