# This Python file uses the following encoding: utf-8


"""
    this code to build the database on sqlite from the csv data
"""

import sqlite3

conn = sqlite3.connect('buses.sqlite')
cur = conn.cursor()
cur.execute("DROP TABLE IF EXISTS Buses")
cur.execute("CREATE TABLE IF NOT EXISTS Buses(number INTEGER,stations INTEGER,duplicate INTEGER)")

buses_txt = open("bus_txt.txt")
content = buses_txt.readlines()

for line in content:
    words = line.split(',')
    count = len(words)-1
    cur.execute("SELECT stations FROM Buses WHERE number = ?",(words[0],))
    row = cur.fetchone()
    if row is None:
        cur.execute("INSERT INTO Buses (number,stations) VALUES (?,?)",(words[0],count))
    else:
        cur.execute("UPDATE Buses set duplicate = duplicate+1 WHERE number = ?",(words[0],))
conn.commit()
cur.close()