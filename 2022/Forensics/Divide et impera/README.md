After short pcap analysis we can find, that we have some HTTP	POST connections to 54.194.203.20	that looks suspicious: 

    513	3.406017	10.0.2.15	54.194.203.20	HTTP	9040	POST /upload HTTP/1.1 
    568	3.460408	10.0.2.15	54.194.203.20	HTTP	4659	POST /upload HTTP/1.1 
    615	3.488747	10.0.2.15	54.194.203.20	HTTP	4660	POST /upload HTTP/1.1 
    650	3.506010	10.0.2.15	54.194.203.20	HTTP	2156	POST /upload HTTP/1.1 
Here we can find files `image.jpg`, `file.xls`, `photo.zip` and `binary_file` were uploaded to `54.194.203.20` server. Lets take a look at them. Firstly, we need to extract files from pcap (note, that files, extracted via `File->Export Objects->HTTP` have `Content Disposition` header inside, so need to strip this header (till `0D 0A 0D 0A` in hex), or extract via `Export Packet Bytes` button, or any packet analysis tool/framework (scapy, for example)). Then we can notice, that we have issues with opening these files, maybe they were encrypted, or we have some stego here? Lets check basic info about these files:

    ls -la
    -rw-r--r-- 1 kali kali 40960 Dec  3 03:38 file1_image.jpg
    -rw-r--r-- 1 kali kali 40960 Dec  3 03:38 file2_file.xls
    -rw-r--r-- 1 kali kali 40960 Dec  3 03:38 file3_photo.zip
    -rw-r--r-- 1 kali kali 18014 Dec  3 03:39 file4_binary_file
    file *
    file1_image.jpg:   Keepass password database 2.x KDBX
    file2_file.xls:    data
    file3_photo.zip:   DOS executable (COM, 0x8C-variant)
    file4_binary_file: data

Lets try to get access to first file:

`kpcli --kdb=file1_image.jpg`

kpcli asks for a password, so we need to try to brute it:

`keepass2john file1_image.jpg > filehash`

`john --wordlist=/opt/wordlists/rockyou.txt filehash   `   

    Using default input encoding: UTF-8
    Loaded 1 password hash (KeePass [SHA256 AES 32/64])
    Cost 1 (iteration count) is 60000 for all loaded hashes
    Cost 2 (version) is 2 for all loaded hashes
    Cost 3 (algorithm [0=AES 1=TwoFish 2=ChaCha]) is 0 for all loaded hashes
    Will run 2 OpenMP threads
    Press 'q' or Ctrl-C to abort, almost any other key for status
    johnny           (file1_image.jpg)     
    1g 0:00:00:01 DONE (2022-12-05 13:04) 0.6289g/s 171.0p/s 171.0c/s 171.0C/s jackie..school
    Use the "--show" option to display all of the cracked passwords reliably
    Session completed. 
Great, lets open file:

`kpcli --kdb=file1_image.jpg`        

    Provide the master password: *************************
    Couldn't load the file file1_image.jpg
    
    Error(s) from File::KeePass:
    encrypt: datasize not multiple of blocksize (16 bytes) at /usr/share/perl5/File/KeePass.pm line 1047.

Looks like these files are splitted version of one single kdbx file. 

`cat file1_image.jpg file2_file.xls file3_photo.zip file4_binary_file > db.kdbx`

`kpcli --kdb=db.kdbx`                                  

    Provide the master password: *************************
    
    KeePass CLI (kpcli) v3.8.1 is ready for operation.
    Type 'help' for a description of available commands.
    Type 'help <command>' for details on individual commands.
    
    kpcli:/> 
Here we can find near 2k secrets in General folder with next pattern: 

    Title: P0013
    Uname: root
     Pass: kSEw8UHRofHh0aHBwgJC4nICIsIxwcK
      URL: 
    Notes: 
After exporting secrets we cant find any anomalies, just 2k similar secrets. Cyberchef says, that it looks like base64 encoded info, so if we try to decode fist secret, we can get `ÿØÿà..JFIF..........ÿÛ.`, which looks like image. SO, concatenate all raws, convert from base64 and open it. After this we will see picture with suspicious Fry, who hints at nesting, or something like that. Its Binwalk time: 

`base64 -d image> file`

`binwalk file`        

    DECIMAL       HEXADECIMAL     DESCRIPTION
    --------------------------------------------------------------------------------
    0             0x0             JPEG image data, JFIF standard 1.01
    50776         0xC658          gzip compressed data, has original file name: "flag", from Unix, last modified: 2022-12-02 18:29:52
after flag extraction we have flag{ThereOnceWasAKidWhosePasswordsLaidAcrossAllSitesTheyWereTheSame}.

Profit \o/
