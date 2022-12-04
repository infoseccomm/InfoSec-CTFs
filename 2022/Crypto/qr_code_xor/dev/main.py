def xor(data: list, key: list) -> list:
    return [data[i] ^ key[i % len(key)] for i in range(len(data))]


# Create task

with open('original_qr.bmp', 'rb') as f:
    qr_data = list(f.read())

with open('random_seq.bin', 'rb') as f:
    rand_data = list(f.read())[:28147]

with open('../task/qr_xor_key.bmp', 'wb') as f:
    f.write(bytearray(xor(qr_data, rand_data)))

# Solve task

with open('../task/qr_xor_key.bmp', 'rb') as f:
    encrypt_data = list(f.read())

for key_len in range(2, 50001):
    last_part_len = (rem_len if (rem_len := len(encrypt_data) % key_len) else key_len)
    test_key_part = xor(encrypt_data[-last_part_len - key_len:-last_part_len - key_len + 2], [0xFF])
    header = xor(encrypt_data[:2], test_key_part)
    if header == [0x42, 0x4D]:
        find_key = xor(encrypt_data[-last_part_len - key_len:-last_part_len], [0xFF])
        with open('../writeup/decrypt_qr_' + str(key_len) + '.bmp', 'wb') as f:
            f.write(bytearray(xor(encrypt_data, find_key)))
