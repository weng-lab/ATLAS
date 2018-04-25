#!/usr/bin/perl

use strict;

# truncate TCRs
# renumber TCR if necessary
# truncate MHC (check to make sure numbering is OK)

# COMMAND
# ./my_truncate_tcr_complexes [PDB] [pmhc_chains] [tcr_chains]
# [pmhc_chains] = [alpha][beta][peptide] --- beta is optional depending on class, peptide always last 
# [tcr_chains] = [alpha][beta]
# Ex.
# ./my_truncate_tcr_complexes 1AO7 DHC AB


my $pdb = $ARGV[0];
my $pmhc_chains = $ARGV[1];
my $tcr_chains = $ARGV[2];
my %chain_hash;

if (($pdb eq "") || ($pmhc_chains eq "") || ($tcr_chains eq ""))
	{ die("usage: my_truncate_tcr_complexes.pl [PDB] [pmhc_chains] [tcr_chains]\n"); }

my $classII = 0;
if (length($pmhc_chains) == 3) { $classII = 1; }

if ($classII == 1) {
    %chain_hash = (
        substr($pmhc_chains, 0,1) => "A",
        substr($pmhc_chains, 1,1) => "B",
        substr($pmhc_chains, 2,1) => "C",
        substr($tcr_chains, 0,1) => "D",
        substr($tcr_chains, 1,1) => "E",
    );

} else {
    %chain_hash = (
        substr($pmhc_chains, 0,1) => "A",
        substr($pmhc_chains, 1,1) => "C",
        substr($tcr_chains, 0,1) => "D",
        substr($tcr_chains, 1,1) => "E",
    );
}

# get the mhc and peptide
open(PDB, "$pdb.pdb") || die("unable to open pdb file: $pdb.pdb\n");
my @pdb_lines = <PDB>;
close(PDB);

my $trunc_pdb = $pdb . ".trunc.pdb";
open(PDBOUT, "> $trunc_pdb") || die("unable to open output pdb file: $trunc_pdb\n");

# get the MHC; will just use residue numbering here
my $mhc_chain = substr($pmhc_chains, 0, 1);
my @mhc_lines = ();
foreach my $line (@pdb_lines)
{
if ((substr($line, 0, 4) eq "ATOM") && (substr($line, 21, 1) eq $mhc_chain))
{
    if (substr($line, 22, 4) eq " 181") { last; } # truncate at residue 180
    elsif (($classII == 1) && (substr($line, 22, 4) eq "  84")) { last; } # truncate at residue 83
    my $line2 = $line;
    substr($line2, 21, 1) = "A";
    push @mhc_lines, $line2;
}
}

my @mhc_b_lines = ();
if ($classII == 1)
{
my $mhc_b_chain = substr($pmhc_chains, 1, 1);
foreach my $line (@pdb_lines)
{
    if ((substr($line, 0, 4) eq "ATOM") && (substr($line, 21, 1) eq $mhc_b_chain))
    {
	if (substr($line, 22, 4) eq "  94") { last; } # truncate at residue 93
	my $line2 = $line;
	substr($line2, 21, 1) = "B";
	push @mhc_b_lines, $line2;
    }
}
}

# get the peptide
my $pep_chain = substr($pmhc_chains, length($pmhc_chains)-1, 1);
my @pep_lines = ();
foreach my $line (@pdb_lines)
{
if ((substr($line, 0, 4) eq "ATOM") && (substr($line, 21, 1) eq $pep_chain))
{
    my $line2 = $line;
    substr($line2, 21, 1) = "C";
    push @pep_lines, $line2;
}
}

# get the TCR alpha chain
my $tcra_chain = substr($tcr_chains, 0, 1);
my @tcra_lines = ();
my $line_num = 0;
my $alpha_need_renum = 0;
open(TMP, "> temp1.pdb") || die("unable to open file: temp1.pdb\n");
foreach my $line (@pdb_lines)
{
if ((substr($line, 0, 4) eq "ATOM") && (substr($line, 21, 1) eq $tcra_chain))
{
    my $line2 = $line;
    substr($line2, 21, 1) = "D";
    print TMP $line2;
    $tcra_lines[$line_num++] = $line2;
}
}
if ($alpha_need_renum == 1) { print "$pdb TCRa renumbering needed!\n"; }
close(TMP);

# perform FAST alignment to get the last residue and renumber as necessary
my $fast_cmd = "~/Research/TCR/FAST/fast-linux-64 1AO7.D.pdb temp1.pdb";
my $fast_out = `$fast_cmd`;
my %alpha_map = ();
my @fast_lines = split("\n", $fast_out);
my $last_alpha_res = "1000";
my $past_last_alpha_res = "1000";
foreach my $line (@fast_lines)
{
if ($line =~ /[A-Z]{3} [A-Z]  [A-Z] [A-Z]{3}/)
{
    my $res1 = substr($line, 1, 5);
    my $res2 = substr($line, 21, 5);
    $alpha_map{$res2} = $res1;
    if ($res1 eq " 116 ") { $last_alpha_res = $res2; }
    elsif ($res1 eq " 117 ") { $past_last_alpha_res = $res2; }
}
}


# get the TCR beta chain
my $tcrb_chain = substr($tcr_chains, 1, 1);
my @tcrb_lines = ();
$line_num = 0;
my $beta_need_renum = 0;
open(TMP2, "> temp2.pdb") || die("unable to open file: temp2.pdb\n");
foreach my $line (@pdb_lines)
{
if ((substr($line, 0, 4) eq "ATOM") && (substr($line, 21, 1) eq $tcrb_chain))
{
    my $line2 = $line;
    substr($line2, 21, 1) = "E";
    print TMP2 $line2;
    $tcrb_lines[$line_num++] = $line2;
}
}
if ($beta_need_renum == 1) { print "$pdb TCRb renumbering needed!\n"; }
close(TMP2);

# perform FAST alignment to get the last residue and renumber as necessary
$fast_cmd = "~/Research/TCR/FAST/fast-linux-64 1AO7.E.pdb temp2.pdb";
$fast_out = `$fast_cmd`;
my %beta_map = ();
@fast_lines = split("\n", $fast_out);
my $last_beta_res = "1000";
my $past_last_beta_res = "1000";
foreach my $line (@fast_lines)
{
if ($line =~ /[A-Z]{3} [A-Z]  [A-Z] [A-Z]{3}/)
{
    my $res1 = substr($line, 1, 5);
    my $res2 = substr($line, 21, 5);
    $beta_map{$res2} = $res1;
    if ($res1 eq " 117 ") { $last_beta_res = $res2; }
    elsif ($res1 eq " 118 ") { $past_last_beta_res = $res2; }
}
}

# Get the helix lines
my @helix_lines = ();
foreach my $line (@pdb_lines) {
    if (substr($line, 0, 5) eq "HELIX") {
        my $line2 = $line;
        my $orig_chain1 = substr($line, 19, 1);
        if ($chain_hash{$orig_chain1}) {
            substr($line2, 19, 1) = $chain_hash{$orig_chain1};
        }
        my $orig_chain2 = substr($line, 31, 1);
        if ($chain_hash{$orig_chain2}) {
            substr($line2, 31, 1) = $chain_hash{$orig_chain2};
        }
        push @helix_lines, $line2;
    }
}

# Get the sheet lines
my @sheet_lines = ();
foreach my $line (@pdb_lines) {
    if (substr($line, 0, 5) eq "SHEET") {
        my $line2 = $line;
        my $orig_chain1 = substr($line, 21, 1);
        if ($chain_hash{$orig_chain1}) {
            substr($line2, 21, 1) = $chain_hash{$orig_chain1};
        }
        my $orig_chain2 = substr($line, 32, 1);
        if ($chain_hash{$orig_chain2}) {
            substr($line2, 32, 1) = $chain_hash{$orig_chain2};
        }
        my $orig_chain3 = substr($line, 49, 1);
        if ($chain_hash{$orig_chain3}) {
            substr($line2, 49, 1) = $chain_hash{$orig_chain3};
        }
        my $orig_chain4 = substr($line, 64, 1);
        if ($chain_hash{$orig_chain4}) {
            substr($line2, 64, 1) = $chain_hash{$orig_chain4};
        }
        push @sheet_lines, $line2;
    }
}

# print helix and sheet lines
foreach my $line (@helix_lines) { print PDBOUT $line;}
foreach my $line (@sheet_lines) { print PDBOUT $line;}


# print out all the lines
foreach my $line (@mhc_lines) { print PDBOUT $line; }
foreach my $line (@mhc_b_lines) { print PDBOUT $line; } # in case this is ClassII
foreach my $line (@pep_lines) { print PDBOUT $line; }
print PDBOUT "TER\n";
# my $curr_shift = 0;
# $alpha_need_renum = 1;
my $last_res_found = 0;
foreach my $line (@tcra_lines)
{
my $orig_res = substr($line, 22, 5);
if ($orig_res eq $last_alpha_res) { $last_res_found = 1; }
elsif (($last_res_found == 1) && ($orig_res ne $last_alpha_res)) { last; }
elsif ($orig_res eq $past_last_alpha_res) { last; }
# if ($alpha_need_renum == 1)
# {
#     my $new_res = $alpha_map{$orig_res};
#     if ($new_res ne "") 
#     {
# 	#substr($line, 22, 5) = $new_res; 
# 	my $new_shift = int(substr($new_res, 0, 4)) - int(substr($orig_res, 0, 4)); 
# 	if ($new_shift != $curr_shift)
# 	{
# 	    my $diff = $new_shift - $curr_shift;
# 	    print "changing shift for $pdb D by " . $diff . "\n";
# 	    $curr_shift = $new_shift;
# 	}
#     }
#     else  # use the shift 
#     {
# 	my $new_num = int(substr($orig_res, 0, 4)) + $curr_shift;
# 	#substr($line, 22, 4) = sprintf("%4d", $new_num);
#     }
# }
print PDBOUT $line;
}
# $curr_shift = 0;
$last_res_found = 0;
foreach my $line (@tcrb_lines)
{
my $orig_res = substr($line, 22, 5);
if ($orig_res eq $last_beta_res) { $last_res_found = 1; }
elsif (($last_res_found == 1) && ($orig_res ne $last_beta_res)) { last; }
elsif ($orig_res eq $past_last_beta_res) { last; }
# if ($beta_need_renum == 1)
# {
#     my $new_res = $beta_map{$orig_res};
#     if ($new_res ne "") 
#     {
# 	#substr($line, 22, 5) = $new_res; 
# 	my $new_shift = int(substr($new_res, 0, 4)) - int(substr($orig_res, 0, 4)); 
# 	if ($new_shift != $curr_shift)
# 	{
# 	    my $diff = $new_shift - $curr_shift;
# 	    print "changing shift for $pdb E by " . $diff . "\n";
# 	    $curr_shift = $new_shift;
# 	}
#     }
#     else  # use the shift 
#     {
# 	my $new_num = int(substr($orig_res, 0, 4)) + $curr_shift;
# 	#substr($line, 22, 4) = sprintf("%4d", $new_num);
#     }
# }
print PDBOUT $line;
}
close(PDBOUT);
system('rm temp1.pdb temp2.pdb')
