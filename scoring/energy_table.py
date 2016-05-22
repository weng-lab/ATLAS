#!/usr/bin/env python

import argparse
import os
import pandas as pd
import numpy as np
import subprocess
import re

parser = argparse.ArgumentParser(description='build model complexes and score using Rosetta\'s fixbb app')
parser.add_argument('-f', help='ATLAS Mutants tab-delimited file (ex. Mutants_052016.txt)', type=str, dest='f',required=True)
parser.add_argument('-w', help='Weights file for scoring with Rosetta (ex. weights_1.wts)', type=str, dest='w',required=True)
parser.add_argument('-r', help='path to Rosetta3 (ex. /home/borrmant/Research/TCR/rosetta/rosetta-3.5/)', type=str, dest='ros_path', required=True)
parser.add_argument('-s', help='path to Brian Pierce\'s TCR-pMHC structure database (ex. /home/borrmant/Research/TCR/tcr_structure_database/all/)',
	type=str, dest='struct_path', required=True) 
args = parser.parse_args()
