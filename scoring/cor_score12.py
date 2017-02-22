#!/usr/bin/env python
import numpy as np
import matplotlib.pyplot as plt

def plot_experiment_vs_predict(dGs, predictions, r):
	

	fig, ax = plt.subplots(figsize=(3.7,3.2))
	#ax.plot(range(-15,1), range(-15,1), c='r', linewidth=2)
	ax.spines['right'].set_visible(False)
	ax.spines['top'].set_visible(False)
	ax.spines['left'].set_linewidth(2)
	ax.spines['bottom'].set_linewidth(2)
	ax.yaxis.set_ticks_position('left')
	ax.xaxis.set_ticks_position('bottom')
	ax.xaxis.set_tick_params(width=2)
	ax.yaxis.set_tick_params(width=2)
	#ax.set_xlim([-15,-4])
	#ax.set_ylim([-12,-2])
	ax.scatter(dGs, predictions, s=8)
	xticks = map(int,ax.get_xticks().tolist())
	yticks = map(int, ax.get_yticks().tolist())
	ax.set_xticklabels(xticks, fontsize=9)
	ax.set_yticklabels(yticks, fontsize=9)
	ax.text(-14, 900, 'r = ' + str(round(r[0,1], 2)), fontsize=14)
	ax.set_xlabel(r'Experimentally measured $\Delta$G (kcal/mol)', fontsize=10)
	ax.set_ylabel(r'$\Delta$$G_{BIND}$ Rosetta score', fontsize=10)
	plt.tight_layout()
	plt.savefig('cor_score12.png', dpi=300)
	plt.close()
	return

def main():
	IN = open('energy_table_score12.txt', 'r')
	IN.readline()
	exp = []
	pred = []
	for line in IN:
		splitline = line.split('\t')
		dG = splitline[11]
		if dG == '\\N':
			dG = -5.05
		elif float(dG) > -5.05:
			dG = -5.05
		else:
			dG = float(dG)
		exp.append(dG)
		pred.append(float(splitline[-1]))
	r = np.corrcoef(exp, pred)
	
	print len(exp)
	print len(pred)
	plot_experiment_vs_predict(exp, pred, r)


if __name__ == '__main__':
	main()
