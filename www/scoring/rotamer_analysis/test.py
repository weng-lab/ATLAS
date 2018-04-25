from pyrosetta import *
from rosetta import *

init()

pose = pose_from_pdb("1AO7_serine2_rotamer_fixbb.pdb")
print pose.sequence()

empty_vec = rosetta.utility.vector1_unsigned_long()

testy = rosetta.core.pack.dunbrack.rotamer_from_chi(pose.residue(78), empty_vec)
print pose.residue(78).name()
print map(int, list(empty_vec))
print pose.residue(78).chi() 
print '\n'


new_vec = rosetta.utility.vector1_unsigned_long()
testy = rosetta.core.pack.dunbrack.rotamer_from_chi(pose.residue(2), new_vec)
print pose.residue(2).name()
print map(int, list(new_vec))
print pose.residue(2).chi() 
print '\n'

new_vec = rosetta.utility.vector1_unsigned_long()
testy = rosetta.core.pack.dunbrack.rotamer_from_chi(pose.residue(5), new_vec)
print pose.residue(5).name()
print map(int, list(new_vec))
print pose.residue(5).chi() 
print '\n'

new_vec = rosetta.utility.vector1_unsigned_long()
testy = rosetta.core.pack.dunbrack.rotamer_from_chi(pose.residue(6), new_vec)
print pose.residue(6).name()
print map(int, list(new_vec))
print pose.residue(6).chi() 
print '\n'

new_vec = rosetta.utility.vector1_unsigned_long()
testy = rosetta.core.pack.dunbrack.rotamer_from_chi(pose.residue(12), new_vec)
print pose.residue(12).name()
print map(int, list(new_vec))
print pose.residue(12).chi() 
print '\n'

new_vec = rosetta.utility.vector1_unsigned_long()
testy = rosetta.core.pack.dunbrack.rotamer_from_chi(pose.residue(8), new_vec)
print pose.residue(8).name()
print map(int, list(new_vec))
print pose.residue(8).chi() 
print '\n'

new_vec = rosetta.utility.vector1_unsigned_long()
testy = rosetta.core.pack.dunbrack.rotamer_from_chi(pose.residue(4), new_vec)
print pose.residue(4).name()
print map(int, list(new_vec))
print pose.residue(4).chi() 
print '\n'

print pose.total_residue()

print '########################################################'

# for i in range(1,708):
# 	new_vec = rosetta.utility.vector1_unsigned_long()
# 	testy = rosetta.core.pack.dunbrack.rotamer_from_chi(pose.residue(i), new_vec)
# 	print pose.residue(i).name()
# 	print map(int, list(new_vec))
# 	print '\n'

