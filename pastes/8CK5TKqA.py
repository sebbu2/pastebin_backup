#!/usr/bin/env python
from importlib.metadata import *
scripts = entry_points()['console_scripts']
packages = [i.name for i in scripts]
files = [ [i.name, i.value, i.group] for i in scripts]
print( ' '.join(packages) )
