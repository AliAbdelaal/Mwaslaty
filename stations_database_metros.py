# This Python file uses the following encoding: utf-8


"""
    this code to add stations to the database
"""

import pymysql
import csv

db = pymysql.connect("localhost", "root", "ali10395", "Mwaslaty", charset='utf8')
# prepare a cursor object using cursor() method
cursor = db.cursor()
file = open('stations_location_metro.csv')
lines = file.readlines()

# create the station if not exit

sql = "CREATE TABLE IF NOT EXISTS Stations(" \
      "ID INTEGER UNIQUE AUTO_INCREMENT, name VARCHAR(50) UNIQUE NOT NULL , longitude DOUBLE , latitude DOUBLE , metro BIT, bus BIT, PRIMARY KEY (ID)) DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;"

try:
    cursor.execute(sql)
    print("table created successfully")
except:
    print("table was not created")

for row in lines:
    elements = row.split(',')
    if len(elements) > 1:
        found = False
        # check if the station is already there
        sql = "SELECT * FROM Stations WHERE name='%s'" % elements[0]
        cursor.execute(sql)
        try:
            data = cursor.fetchone()[0]
            print("stations %s is already is the DB, updating it" % elements[0])
            sql = "UPDATE Stations SET metro = 1 WHERE name = '%s'" % elements[0]
            print(sql)
            cursor.execute(sql)
            found = True
        except Exception as ex:
            print("this couldn't be done because of ",ex)
            pass
        if not found:
            sql = "INSERT INTO Stations(name,longitude,latitude,metro,bus) VALUE('%s',%f,%f,%d,%d)" % \
                  (elements[0], float(elements[2]), float(elements[1]), 1, 0)
            print(sql)
            try:
                cursor.execute(sql)
            except Exception as ex:
                print("this couldn't be done because of ",ex)
db.commit()

# disconnect from server and files
db.close()
file.close()
