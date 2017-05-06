# This Python file uses the following encoding: utf-8

"""this code to clean the data of the buses came from this website
    http://rhaalaa.blogspot.com.eg/2016/12/cairo-public-transportation-bus.html
"""

new_buses = open("Buses_Data.txt")
txt_to_csv = open('bus_txt.txt','w+')
content = new_buses.readlines()

# replace each - with a comma

for line in content:
    for word in line.split():
        if word in['-','خط','>>'] or '-' in word:
            if word == "خط":
                pass
            elif word in ['-','>>']:
                txt_to_csv.write(',')
            else:
                for letter in word:
                    if letter=='-':
                        txt_to_csv.write(',')
                    else:
                        txt_to_csv.write(letter)
        else:
            txt_to_csv.write(word)
        txt_to_csv.write(" ")
    txt_to_csv.write('\n')

new_buses.close()
txt_to_csv.close()