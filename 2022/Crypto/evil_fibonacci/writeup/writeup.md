# evil_fibonacci

1. We will use polynomials over a field of ```p``` elements. 
When adding, multiplying, and dividing such polynomials, 
operations with their coefficients will be performed over
modulo ```p```.

2. Let ```f_n(x) = a_n + b_n * x``` - be the remainder of dividing the
polynomial ```x^(n + 1)``` to the polynomial ```x^2 - x - 1```. 
Then we get that ```f_0(x) = 0 + 1 * x```, ```f_1(x) = 1 + 1 * x```,
```f_2(x) = 1 + 2 * x```, ```f_3(x) = 2 + 3 * x```, ... . 
Thus, for ```n >= 1``` we have formula:
    ```
    f_n(x) = x * f_(n - 1)(x) mod (x^2 - x - 1) = 
    = b_(n - 1) + ((a_(n - 1) + b_(n - 1) mod p) * x.
    ```
    It follows that ```a_n = F_n, b_n = F_(n + 1)```, where ```F_i``` -
i-th member of the Fibonacci sequence.
3. Since the encryption key is ```F_N``` (the N-th member of the Fibonacci 
sequence), then to obtain it, it is enough to define the constant term
of the polynomial ```x^(N + 1) mod (x^2 - x - 1).``` This can be done
using, for example, mathematics software system Sage.
 
The described solution can be implemented using the following Python code
(with using Sage for finite field operations):
```python
from Crypto.Cipher import AES
from Crypto.Hash import SHA256

p = 2**512 - 569

with open('../task/task.txt', 'r') as f:
    ct = bytes.fromhex(f.readline())
    iv = bytes.fromhex(f.readline())
    N = int(f.readline(), 16)

P.<x> = PolynomialRing(GF(p))
F512.<y>=GF(p^2, modulus=x^2 - x - 1)
fib = str(y^(N + 1)).split(' + ')[-1]

key = SHA256.new(fib.encode()).digest()
aes = AES.new(key, AES.MODE_CTR, nonce=iv)
print(aes.decrypt(ct))
```