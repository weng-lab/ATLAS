#!/usr/bin/env python

from pyrosetta import *
from rosetta import *
from pyrosetta import toolbox
import os
init()

def calculate_chi(my_pose, res_num):
	'''
	Calculate chi angle and rotamer states for residue
	'''
	new_vec = rosetta.utility.vector1_unsigned_long()
	testy = rosetta.core.pack.dunbrack.rotamer_from_chi(my_pose.residue(res_num), new_vec)
	print my_pose.residue(res_num).name()
	chi_angles =  my_pose.residue(res_num).chi()
	print chi_angles
	rotamer_states = map(int, list(new_vec))
	print map(int, list(new_vec))
	return chi_angles, rotamer_states

def get_pose(pdb, is_temp):
	'''
	Return Pyrosetta pose
	'''
	if is_temp:
		toolbox.cleanATOM('../../structures/designed_pdb/' + pdb)
		os.rename('../../structures/designed_pdb/' + pdb[:-4] + '.clean.pdb', pdb[:-4] + '.clean.pdb')
		pose = pose_from_pdb(pdb[:-4] + '.clean.pdb')
		return pose
	else:
		toolbox.cleanATOM('../../structures/true_pdb/' + pdb)
		os.rename('../../structures/true_pdb/' + pdb[:-4] + '.clean.pdb', pdb[:-4] + '.clean.pdb')
		pose = pose_from_pdb(pdb[:-4] + '.clean.pdb')
		return pose

def main():
	# Output file
	OUT = open('point_mutant_rotamer_analysis.out', 'w')
	OUT.write('Temp_PDB\tTrue_PDB\tDesigned_Rotamers_States\tTrue_Rotamer_States\tDesigned_Chi\tTrue_Chi\n')

	# Get fixbb rotamers for mutation
	# designed structure format:
	# <pdb>_<MHC_mutation>_<MHC_mut_chain>_<TCR_mutation>_<TCR_mutation_chain>_<Peptide_mutation>.pdb
	####################################################################################
	# TCR_mut	TCR_mut_chain	MHC_mut	MHC_mut_chain	PEP_mut	true_PDB	template_PDB
	####################################################################################
	# Note: TCR A chain = D chain in PDB; B -> E; 

	#1.
	# Q55H	B	WT	nan	WT	3MV8	3MV7
	# Get template pdb chi angles and rotamer states
	temp_pose = get_pose('3MV7_WT_nan_Q55H_B_WT.pdb', True)
	temp_absolute_res_num = temp_pose.pdb_info().pdb2pose('E', 55)
	temp_chis, temp_rotamers = calculate_chi(temp_pose, temp_absolute_res_num)
	# Get true pdb chi angles and rotmaer states
	true_pose = get_pose('3MV8.pdb', False)
	true_absolute_res_num = true_pose.pdb_info().pdb2pose('E', 55)
	true_chis, true_rotamers = calculate_chi(true_pose, true_absolute_res_num)
	# Write results
	OUT.write('3MV7_WT_nan_Q55H_B_WT.pdb\t3MV8.pdb\t')
	OUT.write(','.join(map(str, temp_rotamers)) + '\t')
	OUT.write(','.join(map(str, true_rotamers)) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(temp_chis)))) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(true_chis)))) + '\n')
	
	#2.
	# WT	nan	WT	nan	V7R	1QSE	1AO7
	# Get template pdb chi angles and rotamer states
	temp_pose = get_pose('1AO7_WT_nan_WT_nan_V7R.pdb', True)
	temp_absolute_res_num = temp_pose.pdb_info().pdb2pose('C', 7)
	temp_chis, temp_rotamers = calculate_chi(temp_pose, temp_absolute_res_num)
	# Get true pdb chi angles and rotmaer states
	true_pose = get_pose('1QSE.pdb', False)
	true_absolute_res_num = true_pose.pdb_info().pdb2pose('C', 7)
	true_chis, true_rotamers = calculate_chi(true_pose, true_absolute_res_num)
	# Write results
	OUT.write('1AO7_WT_nan_WT_nan_V7R.pdb\t1QSE.pdb\t')
	OUT.write(','.join(map(str, temp_rotamers)) + '\t')
	OUT.write(','.join(map(str, true_rotamers)) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(temp_chis)))) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(true_chis)))) + '\n')

	#3.
	# WT	nan	WT	nan	Y5F	3QFJ	1AO7
	# Get template pdb chi angles and rotamer states
	temp_pose = get_pose('1AO7_WT_nan_WT_nan_Y5F.pdb', True)
	temp_absolute_res_num = temp_pose.pdb_info().pdb2pose('C', 5)
	temp_chis, temp_rotamers = calculate_chi(temp_pose, temp_absolute_res_num)
	# Get true pdb chi angles and rotmaer states
	true_pose = get_pose('3QFJ.pdb', False)
	true_absolute_res_num = true_pose.pdb_info().pdb2pose('C', 5)
	true_chis, true_rotamers = calculate_chi(true_pose, true_absolute_res_num)
	# Write results
	OUT.write('1AO7_WT_nan_WT_nan_Y5F.pdb\t3QFJ.pdb\t')
	OUT.write(','.join(map(str, temp_rotamers)) + '\t')
	OUT.write(','.join(map(str, true_rotamers)) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(temp_chis)))) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(true_chis)))) + '\n')

	#4.
	# WT	nan	WT	nan	F6L	3VXS	3VXR
	# Get template pdb chi angles and rotamer states
	temp_pose = get_pose('3VXR_WT_nan_WT_nan_F6L.pdb', True)
	temp_absolute_res_num = temp_pose.pdb_info().pdb2pose('C', 6)
	temp_chis, temp_rotamers = calculate_chi(temp_pose, temp_absolute_res_num)
	# Get true pdb chi angles and rotmaer states
	true_pose = get_pose('3VXS.pdb', False)
	true_absolute_res_num = true_pose.pdb_info().pdb2pose('C', 6)
	true_chis, true_rotamers = calculate_chi(true_pose, true_absolute_res_num)
	# Write results
	OUT.write('3VXR_WT_nan_WT_nan_F6L.pdb\t3VXS.pdb\t')
	OUT.write(','.join(map(str, temp_rotamers)) + '\t')
	OUT.write(','.join(map(str, true_rotamers)) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(temp_chis)))) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(true_chis)))) + '\n')

	#5.
	# WT	nan	WT	nan	E5D	4PRH	4PRI
	# Get template pdb chi angles and rotamer states
	temp_pose = get_pose('4PRI_WT_nan_WT_nan_E5D.pdb', True)
	temp_absolute_res_num = temp_pose.pdb_info().pdb2pose('C', 5)
	temp_chis, temp_rotamers = calculate_chi(temp_pose, temp_absolute_res_num)
	# Get true pdb chi angles and rotmaer states
	true_pose = get_pose('4PRH.pdb', False)
	true_absolute_res_num = true_pose.pdb_info().pdb2pose('C', 5)
	true_chis, true_rotamers = calculate_chi(true_pose, true_absolute_res_num)
	# Write results
	OUT.write('4PRI_WT_nan_WT_nan_E5D.pdb\t4PRH.pdb\t')
	OUT.write(','.join(map(str, temp_rotamers)) + '\t')
	OUT.write(','.join(map(str, true_rotamers)) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(temp_chis)))) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(true_chis)))) + '\n')

	#6.
	# WT	nan	WT	nan	L6M	4G9F	4G8G
	# Get template pdb chi angles and rotamer states
	temp_pose = get_pose('4G8G_WT_nan_WT_nan_L6M.pdb', True)
	temp_absolute_res_num = temp_pose.pdb_info().pdb2pose('C', 6)
	temp_chis, temp_rotamers = calculate_chi(temp_pose, temp_absolute_res_num)
	# Get true pdb chi angles and rotmaer states
	true_pose = get_pose('4G9F.pdb', False)
	true_absolute_res_num = true_pose.pdb_info().pdb2pose('C', 6)
	true_chis, true_rotamers = calculate_chi(true_pose, true_absolute_res_num)
	# Write results
	OUT.write('4G8G_WT_nan_WT_nan_L6M.pdb\t4G9F.pdb\t')
	OUT.write(','.join(map(str, temp_rotamers)) + '\t')
	OUT.write(','.join(map(str, true_rotamers)) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(temp_chis)))) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(true_chis)))) + '\n')

	#7.
	# WT	nan	WT	nan	K9E	3QIW	3QIU
	# Get template pdb chi angles and rotamer states
	temp_pose = get_pose('3QIU_WT_nan_WT_nan_K9E.pdb', True)
	temp_absolute_res_num = temp_pose.pdb_info().pdb2pose('C', 9)
	temp_chis, temp_rotamers = calculate_chi(temp_pose, temp_absolute_res_num)
	# Get true pdb chi angles and rotmaer states
	true_pose = get_pose('3QIW.pdb', False)
	true_absolute_res_num = true_pose.pdb_info().pdb2pose('C', 9)
	true_chis, true_rotamers = calculate_chi(true_pose, true_absolute_res_num)
	# Write results
	OUT.write('3QIU_WT_nan_WT_nan_K9E.pdb\t3QIW.pdb\t')
	OUT.write(','.join(map(str, temp_rotamers)) + '\t')
	OUT.write(','.join(map(str, true_rotamers)) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(temp_chis)))) + '\t')
	OUT.write(','.join(map('{:.2f}'.format, map(float,list(true_chis)))) + '\n')


if __name__ == '__main__':
	main()