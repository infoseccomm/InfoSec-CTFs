from pwn import *

p = remote ("0.0.0.0", 2022)

t = 0
f = 0

def important(number):
	for i in range(int(number/2019)):
		if (number - (i*2019)) % 2021 == 0:
			return b"True"
	return b"False"



while True:

	print (p.recvline())
	tmp = p.recvline()
	try:
		number = int(str((tmp.decode())[:-1]))
		print (number)
	except ValueError:
		print (tmp)
		break

	print (important(number))
	if important(number) == b"True":
		t += 1
	else:
		f += 1
	print (p.recvline())
	p.sendline(important(number))


print (t)
print (f)