
class PDB:
	'''
	Class for all PDB functions
	'''
	aa_map = {'ALA':'A', 'ARG':'R', 'ASP':'D',
			'ASN':'N', 'CYS':'C', 'GLU':'E', 
			'GLN':'Q', 'GLY':'G', 'HIS':'H',
			'ILE':'I', 'LEU':'L', 'LYS':'K',
			'MET':'M', 'PHE':'F', 'PRO':'P',
			'SER':'S', 'THR':'T', 'TRP':'W',
			'TYR':'Y',  'VAL':'V'
			}

	def __init__(self, pdb_file):
		self.ID = pdb_file[:4]
		self.FH = open(pdb_file, 'r')
		self.full_string = open(pdb_file, 'r').read()

	def chain_sequence_list(self):
		'''
		Parse PDB file and output list of tuples matching 
		chain with amino acid sequence 
		'''
		aa_col = 3
		chain_col = 4
		res_num_col = 5


		chain_sequence = {}
		aa_list = []
		chain_list = []
		res_num_list = []
		
		for line in self.FH:
			splitline = line.strip().split()
			if splitline[0] == 'ATOM':
				aa_list.append(splitline[aa_col])
				chain_list.append(splitline[chain_col])
				res_num_list.append(splitline[res_num_col])

		if (len(aa_list) != len(chain_list)) or (len(chain_list) != len(res_num_list)):
			print 'ERROR: unequal lengths found parsing PDB'
			quit()

		# Parse lists
		chain_sequence = []
		seq = PDB.aa_map[aa_list[0]] 
		old_res_num = res_num_list[0]
		old_chain = chain_list[0]
		for idx in range(1, len(chain_list)):
			current_chain = chain_list[idx]
			current_res_num = res_num_list[idx]
			if current_chain != old_chain:
				# Save chain data
				chain_sequence.append((old_chain, seq))
				seq = PDB.aa_map[aa_list[idx]]
			else:
				if current_res_num != old_res_num:
					# Save new amino acid data
					seq = seq + PDB.aa_map[aa_list[idx]]
			# Update
			old_res_num = current_res_num
			old_chain = current_chain
		# Final chain
		chain_sequence.append((old_chain, seq))

		return chain_sequence
		






		
