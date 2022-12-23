# bluetooth-scanner-activity-monitor
An activity monitor for physical locations that scans for bluetooth devices

This project scans for bluetooth devices, takes a picture from the webcam and then dumps the devices found, timestamp and picture location into a MySQL database.

We use bluetoothctl to scan for bluetooth devices. We have the scan run for 15 seconds.  The output is piped through grep to only grab the lines with "device" in them.  This is then saved to bt.txt.

Requirements:
- Install LAMP Stack
  - Create Bluetooth database with Log table
  - Create bt_user account and give privileges to bluetooth database.
- Install Open CV for Python
- Python 3
- All files are in /var/www/html directory

This implementation has the python script be manually triggered and then it simply goes through a while True: loop that has a sleep of 60 seconds.

Might be better to have a cron job fire off every minute and eliminate the sleep in the python script

Code explaination:
take-pic-mysql-loop.py is the main script that triggers OpenCV to take a picture, bluetoothctl to scan for devices and inputs results to MySQL
bt-mysql-report.php is a basic ASCII dashboard to show device activity over different periods of time
bt-mysql-loop-picture-report.php shows a per minute devices found, a picture and a timestamp

Scrap paper:

Demo system is Ubuntu Desktop

Bluetoothctl —timeout 10 scan on | grep Device > bt.txt

Sudo apt install pip

Pip install opencv-python

Create database bluetooth

Create table log
	- id int primary key auto_increment
	- timestamp double
	- pic name text
	- bt-list text

Create user bt_user
create bt_user identified by '123456';
GRANT ALL PRIVILEGES ON bluetooth.* TO 'bt_user';

Localhost, bluetooth, log, bt_user, 123456

