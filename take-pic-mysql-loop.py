#This script take a picture using the webcam and opencv
#It scans for Bluetooth devices
#It then inserts a timestamp, picture name, and Bluetooth Devices in MySQL Database

import cv2
import time
import os
import mysql.connector

#mysql connection
mydb = mysql.connector.connect(
    host="localhost",
    user="bt_user",
    password="123456",
    database="bluetooth"
)
mycursor = mydb.cursor()

while True:

    timestamp = time.time()

    #take Picture
    cam_port = 0
    cam = cv2.VideoCapture(cam_port)
    result, image = cam.read()

    if result:
        pic_name = ("./pics/"+str(timestamp)+".png")
        cv2.imwrite("./pics/"+str(timestamp)+".png", image)

    else:
        print("No image detected. Please! try again")

    cam.release()

    #Detect Bluetooth Devices
    cmd = ('bluetoothctl --timeout 15 scan on | grep Device > bt.txt')
    os.system(cmd)

    #read from bt.txt file created by bluetoothctl and turn into a string to insert into mysql
    bt_file = open("bt.txt", "r")
    file_content = bt_file.read()

    content_list = file_content.split("\n")
    bt_file.close()

    bt_list =[]
    for i in range(len(content_list)):
        line = content_list[i]
        array = line.split(" ")
        if len(array) > 1:
            bt_list.append(array[2])

    bt_list = str(bt_list)
    bt_list = bt_list.strip("[]")
    bt_list = bt_list.replace("'", "")
    bt_list = bt_list.replace(" ", "")

    #Insert into MySQL
    sql = "insert into log(timestamp,pic_name,bt_list) values(%s, %s, %s)"
    val = (timestamp,pic_name,bt_list)
    mycursor.execute(sql,val)

    mydb.commit()

    print(mycursor.rowcount, "record inserted.")
    print(str(timestamp) +" | "+ pic_name +" | "+bt_list)
    
    #pause for 1 minute
    time.sleep(60)