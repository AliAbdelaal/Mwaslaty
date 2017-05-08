# this code to find the matched stations


from difflib import SequenceMatcher
import csv

def similar(a, b):
    return SequenceMatcher(None, a, b).ratio()



# the source file of the buses and it's stations

source_file = open("bus_and_stations.csv","r")
accepted_file = open("accepted.txt","r+")
rejected_file = open("rejected.txt","r+")
lines = source_file.readlines()
lines = [x.strip().split(",") for x in lines]

accepted = [(line[:-1].split(",")[0],line[:-1].split(",")[1]) for line in accepted_file.readlines()]
rejected = [(line[:-1].split(",")[0],line[:-1].split(",")[1]) for line in rejected_file.readlines()]

for (line_index,line) in enumerate(lines):
    for (sub_index,word) in enumerate(line):
        if sub_index == 0:
            continue
        else:
            for (sub_line_index,sub_line) in enumerate(lines[line_index+1:]):
                for (sub_word_index,sub_word) in enumerate(sub_line):
                    if sub_word_index == 0:
                        continue
                    ratio = similar(word, sub_word)
                    if ratio > 0.9:
                        # print (sub_word,"was changed to ",word)
                        lines[sub_line_index+line_index+1][sub_word_index] = word
                    elif ratio > 0.75:
                        if (word,sub_word) in accepted:
                            # print(sub_word, "was changed to ", word, "this was learned !")
                            lines[sub_line_index + line_index + 1][sub_word_index] = word
                        elif (word,sub_word) in rejected:
                            pass
                        else:
                            print("confussion here, help please!!")
                            print(sub_word,"and",word,"merge ? :",end="")
                            x = input("")
                            if x.lower() == 'y':
                                # print(sub_word, "was changed to ", word)
                                lines[sub_line_index + line_index + 1][sub_word_index] = word
                                learned = sub_word
                                accepted.append((word,sub_word))
                                accepted_file.write(word+","+sub_word+"\n")
                                accepted.append((sub_word,word))
                                accepted_file.write(sub_word+","+word+"\n")
                            else:
                                learn_to_reject = sub_word
                                rejected.append((word,sub_word))
                                rejected_file.write(word+","+sub_word+"\n")
                                rejected.append((sub_word,word))
                                rejected_file.write(sub_word+","+word+"\n")
    print("done with bus",line_index, "out of ",len(lines))

output = open("bus_and_stations_updated.csv","w+")
for line in lines:
    myString = ",".join(line)
    myString += "\n"
    output.write(myString)
output.close()
accepted_file.close()
rejected_file.close()
source_file.close()
