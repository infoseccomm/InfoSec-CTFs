# qr_code_xor

1. Note that the file with the encrypted QR code has the ".bmp" format. If you keep the usual
black and white QR code in this format and examine the contents of the file in a Hex editor,
you can understand that most of the bytes in the file are either 0xFF (white pixel) 
or 0x00 (black pixel).
2. It can be assumed that the end of the encrypted file contained exclusively bytes 0xFF 
(they form the bottom of the white frame that delimits the QR code).
Given that the file size exceeds 4 * 10^6 bytes, the number of white pixels at the end of the file
must be greater than 5 * 10^4 bytes (maximum key length).
3. We can assume that the xor operation is performed block by block, and the block size is equal 
to the key length. This means that at the end of the encrypted file there are several blocks in
which each byte of the key was xor-ed with 0xFF.
4. Since the last block may be incomplete (if the key length is not a multiple of the file length),
let's select the penultimate block. We find the length of the last block as the remainder of the
division of the file length by the expected key length. Then the penultimate block starts
with a byte, the distance from the end of the file to which is equal to the expected length of the
key plus the length of the last one block.
5. To understand if we are using the correct key length, we can recover the first two bytes of 
the key (for this, we need to xor the first two bytes of the penultimate block with 0xFF).
If we xor the first two bytes of the key with the first two bytes of the encrypted file,
it should be [0x42, 0x4D] (header of any ".bmp" file).
6. Find all key lengths for which the first two bytes of the file will be correctly decrypted.
For the obtained values, we construct keys using xor of the penultimate block of the encrypted
file with 0xFF.
7. Using the found keys, we will try to decrypt the file. You can manually view all found files
to find the one that is displayed correctly.
8. Having scanned the code in the found file, we will get the flag.

The described solution can be implemented using the following Python code:
```python
def xor(data: list, key: list) -> list:
    return [data[i] ^ key[i % len(key)] for i in range(len(data))]

with open('qr_xor_key.bmp', 'rb') as f:
    encrypt_data = list(f.read())

for key_len in range(2, 50001):
    last_part_len = (rem_len if (rem_len := len(encrypt_data) % key_len) else key_len)
    test_key_part = xor(encrypt_data[-last_part_len - key_len:-last_part_len - key_len + 2], [0xFF])
    header = xor(encrypt_data[:2], test_key_part)
    if header == [0x42, 0x4D]:
        find_key = xor(encrypt_data[-last_part_len - key_len:-last_part_len], [0xFF])
        with open('decrypt_qr_' + str(key_len) + '.bmp', 'wb') as f:
            f.write(bytearray(xor(encrypt_data, find_key)))
```