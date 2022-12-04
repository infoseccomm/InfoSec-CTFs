# qr_code_xor

Alice decided to send Bob a QR code. To do this, she generated a key using a physical
random number generator, the length of which did not exceed 50,000 bytes.
Next, she performed a byte-by-byte xor operation of QR code with a repeating key.
Can you recover the QR code?