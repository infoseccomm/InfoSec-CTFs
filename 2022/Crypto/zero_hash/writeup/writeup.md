# zero_hash

_Record of the form ```A ⊕ B``` means the operation "exclusive or" (xor) for elements A and B.
The notation ```A^B``` means raising A to the power of B_

1. Let's carefully study the code given in the condition. It shows that an array of bytes is formed,
which has the form ```{0x06}{0x00}$x{0x01}``` (the entry ```{0x00}$N``` means that the ```0x00```
byte is repeated ```N``` times). Next, the hash value of the generated array is calculated and, if
it is equal to ```0```, encryption is performed using the AES algorithm with a key that is built 
according to the length of the array, that is, ```N + 2```. Thus, the task is to find such a 
value ```N```, in which the hash value of the array will be equal to ```0x00```.

2. Let's check the array calculation algorithm. In it, the variable ```h``` is initialized with the
```offset``` value, then each byte of the hashed data is xored with ```h```, and then ```h``` 
is modified with multiplying ```h``` by ```prime``` modulo ```size```. The result of hashing is the
value ```h``` obtained after processing all bytes of the array.

3. Let's try to approach the problem of calculating the hash value from the group theory point of
view. All multiplicative operations are carried out using modulo ```2^1024```, in other words, in 
the ring ```Z_32 = {0, 1, ..., 2^1024 - 1}```. All elements that can be obtained as a result of the
execution of multiplicative operations form the multiplicative group ```Z*``` of this ring, which
will consist only of odd numbers (because the multiplicative group of the ring of integers includes
numbers which are relatively ```prime``` to the modulus, in other words such as that 
```gcd(a, 2^1024) = 1```, and this obviously holds for all odd numbers and fails for all even
numbers).

4. Then the order of such a group (that is, the number of elements in it) will be equal to
```2^1024 / 2 = 2^1023```. By Lagrange's theorem, any element of ```Z*``` has an order that divides
```2^1023```. Since the order of the element ```a``` is the minimum ```e``` such that 
```a^e = 1 (mod 2^1024)```, then the order of any element from ```Z*``` is easily determined by 
successively checking for equality of the form ```a^(2^i) = 1 (mod 2^1024), i = 0, 1, ..., 1023```.

5. The factor ```prime``` from the condition of the problem ```(0x1000 ... 018D)``` has the order 
```2^1022```, which means that exactly half of the elements of the ```Z*``` can be represented as 
```prime^x mod 2^1024```. ```offset``` number ```(0x5f7a ... 90b3)``` cannot be represented in this 
manner, but the number ```offset + 2``` can be: ```(offset + 2) = prime^x mod 2^1024```, where 
```x = 0x3f02 ... 0f4b```.  In order to find a suitable ```x``` or make sure that it does not exist,
it is necessary to perform a discrete logarithm. In general, it is computationally difficult task 
for modules of large orders, but in this case the result can be obtained relatively quickly using
the Pohlig-Hellman method. This is because the base order of ```prime``` is a degree of ```2```, 
that is, it does not contain large prime divisors.

6. Next, consider the processing of the ```msg``` message of the following form (all operations are
performed using modulo ```2^1024```):
    * The first byte is ```hash = offset ⊕ (offset + 2) = 0x06```. 
   Then ```(offset ⊕ 0x06) * prime = (offset + 2) * prime```.
    * The next ```2^1022 - x - 1``` bytes are ```0x00``` (```2^1022``` is ```prime``` order). 
   After processing them:
   ```hash = (offset + 2) * prime^(2^1022 - x) = prime^x * prime^(2^1022 - x) = prime^(2^1022) = 0x01```.
      + Notes:
        1) The multiplier ```(offset + 2)``` does not change, since the xor of any number from 
        ```0x00``` does not change this number.
        2) ```(offset + 2) = prime^x``` based on the result of the discrete logarithm obtained in 
        step 5.
        3) ```prime^(2^1022) = 1``` by the definition of element order
    * The last byte is ```0x01```. After processing it, ```hash = (0x01 ⊕ 0x01) * prime = 0x00```.

7. Thus, it is clear that the value we are looking for is ```N = 2^1022 - x - 1```, and the length
len of the entire array, which has a null hash value is 
```len = N + 2 = 2^1022 - x - 1 + 2 = 2^1022 - x + 1```.

8. Next, to get the flag, you need to perform a trivial decryption using len to construct an AES
encryption key.

The described solution can be implemented using the following Python code
(with using Sage for calculate discrete logarithm):
```python
from Crypto.Cipher import AES
from Crypto.Hash import SHA256


def calc_ord_element(prime: int, size: int) -> int:
    pw = 2
    for _ in range(int(size).bit_length()):
        if pow(prime, pw, size) == 1:
            return pw
        pw *= 2

        
def calc_discrete_log_for_offset(offset: int, prime: int, size: int) -> int:
    Z = Mod(prime, size)
    num_in_prime_group = offset
    while True:
        try:
            dl = discrete_log(num_in_prime_group, Z)
        except:
            num_in_prime_group += 2
        else:
            break
    return dl


with open('../task/task.txt', 'r') as f:
    ct = bytes.fromhex(f.readline())
    iv = bytes.fromhex(f.readline())

with open('../task/data.txt', 'r') as f:
    hash_offset = int(f.readline(), 16)
    hash_prime = int(f.readline(), 16)
    hash_size = int(f.readline(), 16)

x = calc_discrete_log_for_offset(hash_offset, hash_prime, hash_size)
ord_prime = calc_ord_element(hash_prime, hash_size)
key = SHA256.new(str(ord_prime - x + 1).encode()).digest()
aes = AES.new(key, AES.MODE_CTR, nonce=iv)
print(aes.decrypt(ct))
```