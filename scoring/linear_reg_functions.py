
import numpy as np
import statsmodels.api as sm

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
		return float(prediction)
		

	elif temp_pdb != '':
		train = X[np.all(pdbs != temp_pdb, axis=1), :]
		y = dGs[np.all(pdbs!= temp_pdb, axis=1)]
		res_ols = sm.OLS(y, train).fit()

		prediction = res_ols.predict(energies)
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

def powerset(s):
	'''
	Calculate powerset of an input list
	'''
	result = [[]]
	for x in s:
		newsubsets = [subset + [x] for subset in result]
		result.extend(newsubsets)
	return result

