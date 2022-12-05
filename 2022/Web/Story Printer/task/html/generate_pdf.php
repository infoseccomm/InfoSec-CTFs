<?php

if(isset($_POST['submit_val']))
{

$content = '
<table border=1 align=center width=400>
<tr><td>Title : </td><td>'.$_POST['title'].'</td></tr>
<tr><td>Author : </td><td>'.$_POST['author'].'</td></tr>
<tr><td>Date : </td><td>'.$_POST['date'].'</td></tr>
<tr><td>Text : </td><td>'.$_POST['text'].'</td></tr>
</table>
';

$filename = rand(1,100000).'.html';
$file = fopen('/var/www/html/storage/'.$filename,'w');
fwrite($file,$content);
fclose($file);
$pdf = rand(1,100000).'.pdf';
exec("/usr/local/bin/html-pdf /var/www/html/storage/".$filename." /var/www/html/storage/".$pdf);
sleep(2);
header("Content-Description: File Transfer");
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"". basename($pdf) ."\"");
readfile("/var/www/html/storage/".$pdf);
unlink("/var/www/html/storage/".$filename);
unlink("/var/www/html/storage/".$pdf);
exit(0);
}
?>

