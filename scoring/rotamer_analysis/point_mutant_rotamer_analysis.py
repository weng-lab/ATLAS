#!/usr/bin/env python

from pyrosettta import *
from rosetta import *
init()

####################################################################################
# TCR_mut	TCR_mut_chain	MHC_mut	MHC_mut_chain	PEP_mut	true_PDB	template_PDB
# Q55H	B	WT	nan	WT	3MV8	3MV7
####################################################################################

# Get fixbb rotamers for muation
# designed structure format:
# <pdb>_<MHC_mutation>_<MHC_mut_chain>_<TCR_mutation>_<TCR_mutation_chain>_<Peptide_mutation>.pdb
pose = pose_from_pdb('../../structures/designed_pdb/3MV7_WT_nan_Q55H_B_WT.pdb')

