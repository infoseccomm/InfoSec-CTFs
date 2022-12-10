from ctypes import *


class PasswordPrimitives:

    def __init__(self, io, name=b'AAAA'):
        self.io = io
        self.add_name(name)
        self.key = self._get_key()

    def _validate_prompt(self):
        self.io.recvuntil(b'0) Exit')

    def _get_key(self) -> bytes:
        self.io.recvuntil(b'Master key prepared!\nKey: \n')
        data = self.io.recvline()
        return data[: -1]

    def add_name(self, name: bytes):
        self.io.recvuntil(b'Please type your name:')
        assert len(name) <= 0x20
        self.io.send(name)

    def add_password(self, data: bytes, xored_buf_len=0x40):
        assert len(data) <= 0x100

        self._validate_prompt()
        self.io.sendline(b'1')

        payload_to_send = bytearray(data)
        for i in range(xored_buf_len):
            payload_to_send[i] = payload_to_send[i] ^ self.key[i % 8]

        self.io.send(payload_to_send)

    def read_password(self):
        self._validate_prompt()
        self.io.sendline(b'2')

    def change_name(self, new_name: bytes) -> bytes:
        self._validate_prompt()
        self.io.sendline(b'3')
        self.add_name(new_name)

        self.io.recvuntil(b'Your new name: ')
        return self.io.recvline()
