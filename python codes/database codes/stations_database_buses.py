# This Python file uses the following encoding: utf-8


"""
    this code to add stations to the database
"""

import pymysql
import csv

db = pymysql.connect("localhost", "username", "password", "Mwaslaty", charset='utf8')
# prepare a cursor object using cursor() method
cursor = db.cursor()
file = open('../../clean data/v1.2/location ready data v1.2/stations_location_buses_updated_v1.2.csv')
lines = file.readlines()

# create the station if not exit

sql = "CREATE TABLE IF NOT EXISTS Stations(" \
      "ID INTEGER UNIQUE AUTO_INCREMENT, name VARCHAR(50) UNIQUE NOT NULL , longitude DOUBLE , latitude DOUBLE , metro BIT, bus BIT,centric BIT,buses_count INTEGER , PRIMARY KEY (ID)) DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;"

try:
    cursor.execute(sql)
    print("table created successfully")
except:
    print("table was not created")

for row in lines:
    elements = row.split(',')
    if len(elements) > 1:
        sql = "INSERT INTO Stations(name,longitude,latitude,metro,bus,centric,buses_count) VALUE('%s',%f,%f,%d,%d,%d,%d)" % \
              (elements[0], float(elements[2]), float(elements[1]), 0, 1, 0, 0)
        print(sql)
        try:
            cursor.execute(sql)
        except Exception as ex:
            print("this couldn't be done because of ",ex)
db.commit()

# disconnect from server and files
db.close()
file.close()
