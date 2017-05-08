# this code to count how many buses pass by a station



import pymysql

db = pymysql.connect("localhost", "username", "password", "Mwaslaty", charset='utf8')
# prepare a cursor object using cursor() method
cursor = db.cursor()

sql = "SELECT id FROM Stations"
cursor.execute(sql)
stations_id = cursor.fetchall()

for id in stations_id:
    # find how many time this station was found in the relational table
    sql = "SELECT station_id FROM Bus_stations WHERE station_id=%d"% id[0]
    cursor.execute(sql)
    instances = len(cursor.fetchall())
    print("station with id",id[0],"appeared ",instances,"times")
    sql = "UPDATE Stations SET buses_count = %d WHERE ID = %d"%(instances, id[0])
    cursor.execute(sql)


db.commit()
cursor.close()

