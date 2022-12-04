# weak_vernam

1. Note that we know the first five characters of the plaintext pt1, which corresponds to the
ciphertext ct1, written in the first line of the file "task.txt" - these are the first characters 
of the flag - 'flag{'. Then we can compute the first five characters of key: 
```key[i] = (ct1[i] - pt1[i]) % 256, i = 0..4```
2. Next, we will fix the possible value of the sixth character of the key (key[5]) and try to 
recover the plaintext pt1, that is, the flag. Since key[5] can take any value from 0 to 255, 
then steps 3 and 4 will be performed in a loop for each possible value of key[5].
3. In order to restore pt1, we will proceed as follows: using the known part of the key 
```key[0], key[1], ..., key[5]```, find the first part of the plaintext 
``` pt1[0], pt1[1], ..., pt1[5]``` as ```pt[i] = (ct1[i] - key[i]) % 256, i = 0..5 ```,
using the plaintext part ```pt1[0], pt1[1], ..., pt1[5]``` find the next part of the key 
```key[6], key[7], ..., key[11]``` as ```key[i] = (ct2[i] - pt1[i - 6]) % 256, i = 6..11```,
then with part key ```key[6], key[7], ..., key[11]``` find the next part of the plaintext 
```pt1[6], pt1[7], ..., pt1[11 ]```. We continue this process until we restore pt1 completely.
4. If the sixth character of the key is fixed correctly, then after restoring the plaintext pt1,
its last character must be equal to '}'. If this condition is met, then we have found the flag.

The described solution can be implemented using the following Python code:
```python
part_flag = b'flag{'

with open('../task/task.txt', 'r') as f:
    ct1 = bytes.fromhex(f.readline())
    ct2 = bytes.fromhex(f.readline())
    key = [((ct1[k] - part_flag[k]) % 256) for k in range(len(part_flag))]
    for i in range(0, 256):
        test_key = key + [i]
        flag = [((ct1[k] - test_key[k]) % 256) for k in range(6)]
        for j in range(1, len(ct1) // 6):
            test_key += [((ct2[j * 6 + k] - flag[(j - 1) * 6 + k]) % 256) for k in range(6)]
            flag += [((ct1[j * 6 + k] - test_key[j * 6 + k]) % 256) for k in range(6)]
        if flag[-1] == int.from_bytes(b'}', 'big'):
            print(bytes(flag))
            break
```