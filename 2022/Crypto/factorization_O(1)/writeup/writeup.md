# factorization_O(1)

1. The title of the assignment hints that there must be a way to factorize ```N``` in constant time.
At the same time, its size and method of generation do not contain explicit cryptographic flaws.
So, we can assume that the factorization of ```N``` can be known.
2. Go to http://factordb.com/index.php and look for our ```N```. Unexpectedly, but there is a 
required factorization (it was added by the authors of the task).
3. Thus, the solution of the problem becomes trivial: we calculate ```d```, with the help of which
we decrypt the flag.
 
The described solution can be implemented using the following Python code:
```python
from Crypto.Util.number import long_to_bytes

with open('task.txt', 'r') as f:
    e = int(f.readline()[4:], 16)
    n = int(f.readline()[4:], 16)
    ct = int(f.readline()[5:], 16)

print(n)
# Search n in factordb -> find p & q
p = 28202319379067501490208047967640223972527628887419121174312069871940762446191037116439778835467062167539975479560808963430713316728657821318091974782177587502977103133562514623190596522401979853897604155978389706065529684964456763671042793097939248363898812226603785142150229952531892483051629343135565017009158169764295572681006147649784770674916373016362742532035032732868305514205472359902274368791400942198050857316423038645187897952552037443809257021152791177709722355552601158212401832663511665683464894274793964666346178953264175424315896294371414865822376705827230948258710334712677553560281415434518989952471
q = n // p
d = pow(e, -1, (p - 1) * (q - 1))
print(long_to_bytes(pow(ct, d, n)))
```