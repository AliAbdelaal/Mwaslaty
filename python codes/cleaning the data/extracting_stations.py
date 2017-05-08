# This Python file uses the following encoding: utf-8


"""
    this code to extract the stations from the buses data
"""
import csv

the_file = open('../bus_and_stations_updated.csv')
reader = csv.reader(the_file)
stations = list()

count = 0
for row in reader:
    print('row %d is' % (count), row)
    count += 1
    for col in row:
        if row.index(col) == 0:
            continue
        else:
            col = col.strip()
            if col not in stations:
                stations.append(col)
stations.sort()
print(stations)
print('number of stations : ', len(stations))
the_file.close()
stations_file = open("stations_updated.csv", 'w+')

for station in stations:
    stations_file.write(station+'\n')
stations_file.close()
