# double_G

1. To get the flag, you need to recover the key of the AES encryption algorithm. This key is
formed as a hash value of the concatenation of a known random salt and two encryption keys that 
are used in the block cryptosystem G (```salt || key1 || key2```).

2. Note that both ```key1``` and ```key2``` are 28 bits in size (because the "and" is done 
with ```0x0FFFFFFF```). Accordingly, to solve the problem through bruteforce, it will be 
necessary to sort through on average ```2^(28) * 2^28 / 2 = 2^55``` options, which is a rather
resource-intensive task.

3. Note that first the known plaintext ```pt_str``` is encrypted with the first key ```key1```,
and then the resulting text is encrypted with the second key ```key2```, resulting in a known 
ciphertext ```ct_str```. This construction is not secure from the point of view of cryptography,
as it allows a meet-in-the-middle attack.

4. To do this, perform encryption of ```pt_str``` on all possible values of the key
```key1``` (```2^28``` operations will be performed), and the result of encryption
```res_encr``` of each performed encryption will be added to the associative container 
as a key, and the key ```key1```, on which this encryption was performed, will be the value
of the added pair.

5. Next, we will perform the decryption of ```ct_str``` on all possible values of the key
```key2``` (```2^28``` operations will be performed), while searching for the decryption 
result ```res_decr ``` in the associative container built in the previous step. If such an 
element is found in the container, that is, a meeting occurred (```res_encr``` == ```res_decr```),
then we will consider a pair of keys ```[key1, key2]``` (``` key1``` is in the container by the
key ```res_decr``` and ```key2``` is the current decryption key) as a possible key pair.

6. For each of the resulting key pairs ```[key1, key2]``` we build the encryption key 
```aes_key``` and try to decrypt the flag. One of the pairs will give the expected result.

A meet-in-the-middle attack requires about ```2^29``` operations, so it is desirable to use 
a high-performance language (C++, Rust). It takes about 10 minutes and 3 GB of RAM to perform 
an attack using the following C++ implementation of the G cryptosystem. 
This code implements steps 4 and 5 of the algorithm described above.
```cpp
#include <iostream>
#include <unordered_map>

using namespace std;

uint8_t ROUNDS = 64;
uint8_t S1[16] = {3, 5, 11, 12, 15, 7, 1, 13, 2, 0, 10, 9, 6, 14, 4, 8};
uint8_t S2[16] = {8, 12, 15, 13, 4, 5, 9, 1, 7, 3, 0, 2, 11, 10, 6, 14};

uint8_t encrypt_round(const uint8_t x, const uint8_t k)
{
    uint8_t u = x ^ k;
    uint8_t u1 = (u >> 4) & 0x0F, u2 = u & 0x0F;
    u1 = S1[u1];
    u2 = S2[u2];
    u = (u1 << 4) + u2;
    return ((u << 3) | (u >> 5));
}

uint16_t encrypt_feistel(const uint16_t x, const uint8_t k1, const uint8_t k2, const uint8_t k3, const uint8_t k4)
{
    uint8_t y1 = (x >> 8) & 0xFF, y2 = x & 0xFF;
    for (uint8_t i = 0; i < ROUNDS / 4; i += 1)
    {
        uint8_t tmp = y1;
        y1 = y2;
        y2 = tmp ^ encrypt_round(y2, k1);
        tmp = y1;
        y1 = y2;
        y2 = tmp ^ encrypt_round(y2, k2);
        tmp = y1;
        y1 = y2;
        y2 = tmp ^ encrypt_round(y2, k3);
        tmp = y1;
        y1 = y2;
        y2 = tmp ^ encrypt_round(y2, k4);
    }
    return (y2 << 8) + y1;
}

void encrypt_ecb(const uint32_t key, const uint16_t *in, const uint16_t in_len, uint16_t *out)
{
    uint8_t k1 = (key >> 24) & 0xFF, k2 = (key >> 16) & 0xFF, k3 = (key >> 8) & 0xFF, k4 = key & 0xFF;
    for (uint16_t i = 0; i < in_len; i += 1)
    {
        out[i] = encrypt_feistel(in[i], k1, k2, k3, k4);
    }
}

void decrypt_ecb(const uint32_t key, const uint16_t *in, const uint16_t in_len, uint16_t *out)
{
    uint8_t k1 = (key >> 24) & 0xFF, k2 = (key >> 16) & 0xFF, k3 = (key >> 8) & 0xFF, k4 = key & 0xFF;
    for (uint16_t i = 0; i < in_len; i += 1)
    {
        out[i] = encrypt_feistel(in[i], k4, k3, k2, k1);
    }
}

int main ()
{
    const uint16_t pt[4] = {0x65fe, 0x13fe, 0xd92a, 0xdbc9}; // pt_str from task.txt
    const uint16_t ct[4] = {0x89b9, 0xf2c7, 0xe6dd, 0x32f8}; // ct_str from task.txt
    
    uint16_t tmp[4] = {0x0000, 0x0000, 0x0000, 0x0000};
    unordered_map<uint64_t, uint32_t> key_map;

    cout << "Part 1 (step 4) start!" << endl;
    for (uint32_t key1 = 0;  key1 < 0x0FFFFFFF; key1 += 1)
    {
        encrypt_ecb(key1, pt, sizeof(pt) / sizeof (uint16_t), tmp);
        uint64_t res_encr = ((uint64_t)(tmp[0]) << 48) + ((uint64_t )(tmp[1]) << 32) + ((uint64_t)(tmp[2]) << 16) + tmp[3];
        key_map[res_encr] = key1;
    }

    cout << "Part 2 (step 5) start!" << endl;
    for (uint32_t key2 = 0;  key2 < 0x0FFFFFFF; key2 += 1)
    {
        decrypt_ecb(key2, ct, sizeof(pt) / sizeof (uint16_t), tmp);
        uint64_t res_decr = ((uint64_t)(tmp[0]) << 48) + ((uint64_t )(tmp[1]) << 32) + ((uint64_t)(tmp[2]) << 16) + tmp[3];
        if (auto iter = key_map.find(res_decr); iter != key_map.end())
        {
            cout << "Find possible key pair: [" << iter->second << ", " << key2 << "]" << endl;
        }
    }

    cout << "Done!" << endl;
    return 0;
}
```

As a result of executing this program, we get the following output:
```
Part 1 (step 4) start!
Part 2 (step 5) start!
Find possible key pair: [205170420, 123820663]
Find possible key pair: [205170420, 124330623]
Find possible key pair: [205170420, 252783231]
Find possible key pair: [205170420, 253317751]
Done!
```

Let's use the following Python code to get the flag (step 6 of the algorithm described above):
```python
from Crypto.Cipher import AES
from Crypto.Hash import SHA256

key1, key2_ls = 205170420, [123820663, 124330623, 252783231, 253317751]

with open('task.txt') as f:
    ct = bytes.fromhex(f.readline())
    iv = bytes.fromhex(f.readline())
    salt = f.readline().strip()

for key2 in key2_ls:
    aes_key = SHA256.new((salt + hex(key1)[2:] + hex(key2)[2:]).encode()).digest()
    aes = AES.new(aes_key, AES.MODE_CTR, nonce=iv)
    pt = aes.decrypt(ct)
    if pt.startswith(b'flag{'):
        print(pt)
        break
```