# This Python file uses the following encoding: utf-8


"""
    this code to transfer the txt data into csv
"""
import csv
txt_to_csv = open('bus_txt.txt')
content = txt_to_csv.readlines()
txt_to_csv.close()
with open('buses.csv','w',newline='') as csvFile:
    writer = csv.writer(csvFile,delimiter=',')
    for line in content:
        if len(line.strip()) == 0 :
            continue
        else:
            words = line.strip().split(',')
            writer.writerow(words)