#Test.

Such a problem was discovered on a real project and was made in the form of a CTF task.

1. Run DIRB with standard parameters. Must find the directory and file /flag/flag.txt.
   Along this path, the response code from the server was 403.
   Many other directories and files could also be found. But PHP files were not executed.

2. After unsuccessful attempts to find something, it was necessary to pay attention to the server itself. 
   And to study its configuration errors that would allow you to get the file flag.txt.

   Links to learn:
   https://www.acunetix.com/vulnerabilities/web/apache-configured-to-run-as-proxy/
   https://httpd.apache.org/docs/current/mod/mod_proxy.html

3. Next, make a request in Burp.
   
   GET http://127.0.0.1/flag/flag.txt HTTP/1.1
   Host: infosec-test.chals.io

   And get flag FLAG{M1S$_C0nf1G!}
