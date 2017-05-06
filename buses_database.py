# This Python file uses the following encoding: utf-8


"""
    this code to add buses to the database
"""

import pymysql
import csv

db = pymysql.connect("localhost", "root", "ali10395", "Mwaslaty", charset='utf8')
# prepare a cursor object using cursor() method
cursor = db.cursor()
file = open('buses.csv')
reader = csv.reader(file)

sql = "CREATE TABLE IF NOT EXISTS Buses(" \
      "ID INTEGER UNIQUE AUTO_INCREMENT, name VARCHAR(50) UNIQUE NOT NULL, cost FLOAT , PRIMARY KEY (ID)) DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;"

try:
    cursor.execute(sql)
    print("table created successfully")
except:
    print("table was not created")



for row in reader:
    stations = row[1:]
    sql = "INSERT INTO Buses(name, cost) VALUES('%s', %f)"%(row[0], 2.)
    print(sql)
    try:
        cursor.execute(sql)
    except Exception as ex:
        print("this couldn't be done because of ",ex)
db.commit()




# disconnect from server and files
db.close()
file.close()
