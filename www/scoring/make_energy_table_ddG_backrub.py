#!/usr/bin/env python

DDG_table = open('energy_table_ddG.txt', 'r')

OUT = open('energy_table_ddG_backrub.txt', 'w')

OUT.write(DDG_table.readline())

for ddG_line in DDG_table:
	ddG_idx = ddG_line.split()[1]
	ddG_set = ddG_line.split()[0]
	backrub_table = open('energy_table_backrub.txt', 'r')
	for br_line in backrub_table:
		br_idx = br_line.split()[0]
		if br_idx == ddG_idx:
			OUT.write(ddG_set + '\t' + br_line)
			backrub_table.close()
			break
		
OUT.close()



