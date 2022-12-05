## **Story Printer writeup**

1. Firstly, we need to register into the web application.

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture1.png?token=GHSAT0AAAAAAB3XVI5W63LWRJ5OK6TOPQPUY4OEPXQ)

Here we can note that application shows us max length of username. Besides, application allows to create user with username longer than 10 characters, but there will be no possibility to login.
Later we will use it.

2. 
![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture2.png?token=GHSAT0AAAAAAB3XVI5XSCMUOVYVVB7KLJBSY4OESTQ)

After successful login we see information about what application can do. Besides, we found out that only "Admin" user has access to application's functionality. Obviously, we need to obtain that user to make our way further.

3. We know which user we need and max username length (10 characters). Next step - is to perform SQL trancation attack. More information about it could be found here: https://linuxhint.com/sql-truncation-attack/. We need to take username "Admin", add five spaces and some additional character, for example,: "Admin     a". Everything is ready for registration.

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture3.png?token=GHSAT0AAAAAAB3XVI5WUFOSFZS4GLECXWG4Y4OETEQ)

Now, another "Admin" user exists in the database.

4. Now we have access to application's functionality.

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture4.png?token=GHSAT0AAAAAAB3XVI5WG2BPEEXO7LDQH6X4Y4OETYA)

As we already know, application allows converting our input to PDF file. But firstly, application converts our input to HTML table and only after that to PDF. Knowing that step, thoughts about XSS may come out. However, client-side attacks would not help us.

But if application uses vulnerable module which has security issues in HTML tables processing before turning it into the PDF file, we may have a possibility to get LFI using the following payload:

    <script>x=new XMLHttpRequest;x.onload=function(){document.write(this.responseText)};x.open("GET","file:///etc/passwd");x.send();</script>

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture5.png?token=GHSAT0AAAAAAB3XVI5XSCK65HBVIRKZKSPYY4OEUGA)


After downloading the result we see contents of the /etc/passwd file and user with "flag" name. Flag lies in the file /home/flag/.ssh/id_rsa :

    <script>x=new XMLHttpRequest;x.onload=function(){document.write(this.responseText)};x.open("GET","file:///home/flag/.ssh/id_rsa");x.send();</script>

![enter image description here](https://raw.githubusercontent.com/grazieragazzi/GRZblog/master/Picture6.png?token=GHSAT0AAAAAAB3XVI5XPCK7SPPUWSMBITMGY4OEUUA)

Flag: flag{d0n'7_7runc473_y0ur_570r135}
