from flag import flag
import time
import socket
import os
from _thread import *
import random

host = "0.0.0.0"
port = 2022
ServerSideSocket = socket.socket()
ThreadCount = 0

b1 = 1111
b2 = 9999999

def important(number):
	for i in range(int(number/2019)):
		if (number - (i*2019)) % 2021 == 0:
			return True
	return False

try:
	ServerSideSocket.bind((host, port))
except socket.error as e:
	print(str(e))
print('Socket is listening..')
ServerSideSocket.listen(5)
def multi_threaded_client(connection):
	connection.send(str.encode('Welcome to the InfoSec CTF 2022!\n'))
	cnt = 0
	while True:

		if cnt == 100:
			connection.sendall(flag.encode('utf-8'))
			connection.sendall(str.encode("Congratulations!\n"))
			break
		
		cnt += 1
		number = random.randint(b1,b2)
		connection.sendall(str.encode(str(number)+"\n"))
		connection.sendall(str.encode('Do we like this number?\n'))
		data = (connection.recv(2048)).decode()
		data = str(data)[:-1]

		if data != "True" and data != "False":
			connection.sendall(str.encode('Could not understand you!\n'))
			break

		if important(number):
			if data == "True":
				connection.sendall(str.encode('You are right!\n'))
				continue
			else:
				connection.sendall(str.encode('Nope. Try again!\n'))
				break
		else:
			if data == "False":
				connection.sendall(str.encode('You are right!\n'))
				continue
			else:
				connection.sendall(str.encode('Nope. Try again!\n'))
				break        	

	connection.close()

while True:
	Client, address = ServerSideSocket.accept()
	print('Connected to: ' + address[0] + ':' + str(address[1]))
	start_new_thread(multi_threaded_client, (Client, ))
	ThreadCount += 1
	print('Thread Number: ' + str(ThreadCount))
ServerSideSocket.close()