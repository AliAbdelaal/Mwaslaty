# This Python file uses the following encoding: utf-8


"""
    this code to get the location of stations and update them in stations_location file
"""

import csv
import urllib.parse
import urllib.request
import json
import ssl

# Google API (requires API key)
serviceurl = "http://maps.googleapis.com/maps/api/geocode/json?"

# Deal with SSL certificate anomalies Python > 2.7
scontext = ssl.SSLContext(ssl.PROTOCOL_TLSv1)
# python 2
# scontext = None

file = open("metro_stations.txt")
output_file = open("stations_location_metro.csv", "w+")
buses_stations = open("stations.csv")
founded_stations = buses_stations.readlines()
buses_stations.close()
lines = file.readlines()
file.close()
counter = 0

for line in lines:
    counter += 1
    address = line.strip()
    if len(address) < 1:
        continue
    if line in founded_stations:
        print ("station %s already resolved"%line)
        continue
    print('resolving ', address)

    url = serviceurl + urllib.parse.urlencode({"sensor": "false", "address": address+"Cairo Egypt"})

    print('retrieving url', url)
    uh = urllib.request.urlopen(url, context=scontext)
    data = uh.read()
    print('Retrieved', len(data), 'characters')
    try:
        js = json.loads(data.decode("utf-8"))
        print("json found !")
        if 'status' not in js or (js['status'] != 'OK' and js['status'] != 'ZERO_RESULTS'):
            print('==== Failure To Retrieve ====')
            print(data)
        else:
            lat = js['results'][0]["geometry"]["location"]['lat']
            lng = js['results'][0]["geometry"]["location"]['lng']
            print("station : ", js['results'][0]["formatted_address"])
            print("lat : ", lat, "lng : ", lng)
            if "Egypt" not in data.decode("utf-8"):
                print("this is not in egypt !")
                continue
            output_file.write(address + "," + str(lat) + "," + str(lng) + "\n")
            print("----------station done---------%d out of %d stations done" % (counter, len(lines)))



    except Exception as ex:
        print(ex)
        print("error is %s station" % address)

output_file.close()
