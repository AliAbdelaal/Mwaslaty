# This Python file uses the following encoding: utf-8
# python3

translate_table = {
    1632:'0',   #number 0 in arabic (unicode)
    1633:'1',
    1634:'2',
    1635:'3',
    1636:'4',
    1637:'5',
    1638:'6',
    1639:'7',
    1640:'8',
    1641:'9'    #all the numbers
}

def translate_to_english_numbers(data):
    output = str()
    for letter in data:
        code = ord(letter)
        if code in translate_table:
            output += translate_table[code]
        else:
            output += letter
    return output



arabic = open("buses.txt")
english = open("buses_translated.txt",'w+')

for line in arabic:
    print (translate_to_english_numbers(line),end="")
    english.write(translate_to_english_numbers(line))
arabic.close()
english.close()

