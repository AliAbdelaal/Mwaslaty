# This Python file uses the following encoding: utf-8


"""
    this code to build the bus station relational table
"""

import pymysql
import csv

db = pymysql.connect("localhost", "username", "password", "Mwaslaty", charset='utf8')
# prepare a cursor object using cursor() method
cursor = db.cursor()
sql = "CREATE TABLE IF NOT EXISTS Bus_stations(" \
      "bus_id INTEGER ,station_id INTEGER ,PRIMARY KEY (bus_id,station_id)) DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;"

try:
    cursor.execute(sql)
    print("table created successfully")
except Exception as ex:
    print("table was not created",ex)


# getting the buses data
file = open("buses.csv")
reader = csv.reader(file)

try:
    for row in reader:
        if len(row) < 1:
            continue
        sql = "SELECT id FROM Buses WHERE name= '%s'" % row[0]
        cursor.execute(sql)
        bus_id = cursor.fetchall()[0][0]
        stations_id = list()
        for station in row[1:]:
            sql = "SELECT id FROM Stations WHERE name ='%s'" % station.strip()
            cursor.execute(sql)
            fetched = cursor.fetchall()
            if len(fetched) > 0:
                station_id = fetched[0][0]
                stations_id.append(station_id)
        for station in stations_id:
            try:
                sql = "INSERT INTO Bus_stations(bus_id,station_id) VALUES (%d, %d)"%(bus_id, station)
                print(sql)
                cursor.execute(sql)
            except Exception as ex:
                print("somthing here happend !",ex)
        db.commit()
except Exception as ex:
    print("error :",ex)



cursor.close()
file.close()
