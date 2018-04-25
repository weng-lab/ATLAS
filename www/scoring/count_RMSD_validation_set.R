num_aa_vector <- c(14, 1,1,1,1,1,1,1)
names <- c(1,2,3,4,6,11,12,19)

barplot(num_aa_vector, names.arg = names, xlab = '# of mutant residues per complex',
        ylab='Count')

aa <- c('A', 'V', 'I', 'L', 'M', 'F', 'Y', 'W', 'S', 
       'T', 'N', 'Q', 'C', 'G', 'P', 'R', 'H', 'K', 
       'D', 'E')
aa_count <- c(7,0, 0, 1, 1, 1, 0, 0, 0,
              0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1)

barplot(aa_count, names.arg = aa, xlab= 'Amino Acid', ylab='# of point mutants')

total_aa_count <- c(12, 2, 0, 8, 5, 4, 1, 5, 3, 
                    5, 0, 3, 0, 3, 4, 3, 2, 0, 
                    3, 2)

barplot(total_aa_count, names.arg = aa, xlab= 'Amino Acid', ylab='# of mutants')