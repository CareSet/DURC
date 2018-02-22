Test database

This is a hacked  fork of https://github.com/dalers/mywind
Which is itself an example of the Microsoft Northwind database
The original port of that db structure lives under the northwind_start subdirectory.

It is super helpful to see the diagram:
https://raw.githubusercontent.com/dalers/mywind/master/northwind-erd.png

It is modified specifically as a test platform for https://github.com/CareSet/DURC

The version of the northwind database in this directory splits the original databases 
into multiple database, in order to intentionally add complexity to 
the DURC compiler. It also enforces the DURC naming convention. 

Other specific cases that DURC is supposed to support are modeled in the aaaDurctest database.
This includes a link to the irs database, which is intended to test select2 performance for data sets with real-world (i.e. over 1000) row sizes. 
