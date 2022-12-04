import secrets

# Create task

flag = b'flag{u53_0f_53cr37_k3y_f0r_ww3rn4m_c1ph3r_7w1c3_15_un53cur3}'
key = secrets.token_bytes(len(flag))

with open('../task/task.txt', 'w') as f:
    f.write(''.join(f'{((flag[i] + key[i]) % 256):02x}' for i in range(len(flag))) + '\n')
    shift_flag = flag[-6:] + flag[:-6]
    f.write(''.join(f'{((shift_flag[i] + key[i]) % 256):02x}' for i in range(len(flag))) + '\n')

# Solve task

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
        print(test_key)
        if flag[-1] == int.from_bytes(b'}', 'big'):
            print(i)
            print(bytes(flag))
            break
