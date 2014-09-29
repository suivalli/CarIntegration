#!/usr/bin/env python
# -*- coding: utf-8 -*-
#
#  Make_it_happen.py
#  
#  Copyright 2014 Suido <suido@suido-lauaarvuti>
#  
#  This program is free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
#  
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#  
#  You should have received a copy of the GNU General Public License
#  along with this program; if not, write to the Free Software
#  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
#  MA 02110-1301, USA.
#  
#  
# 0,Alle;17,100;18,200;891,5000;15,80;16,90;1188,A1;19,A2;1063,A2 3L;20,A3;21,A4;1005,A5;22,A6;1231,A7;23,A8;562,Cabriolet;834,Coupe;836,DKW F102;892,NSU Ro 80;1235,Q3;1119,Q5;936,Q7;835,Quattro;24,TT;529,V8;

import csv
import sys


def main():
	
	return 0

if __name__ == '__main__':
	main()
	
def makeItRain(row):
	return row.split(";");

def createCSV(name, row):
	URL = makeItRain(row)
	names = []
	for i in range(0, len(URL)):
		URL[i] = URL[i].replace(",","-")	
		s=URL[i].split("-")
		names.append(s[1])
		
	with open(name, 'wb') as csvfile:
		writer = csv.writer(csvfile, delimiter=';', quotechar='"', quoting=csv.QUOTE_MINIMAL)
		writer.writerow(["Nimi", "URL"])
		for i in range(0,len(URL)):
			writer.writerow([names[i], URL[i]])

createCSV(str(sys.argv[1]), str(sys.argv[2]))	


