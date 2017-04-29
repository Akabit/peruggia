<?php

/*
 * This file is part of Peruggia.
 *
 * Peruggia is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 3 of the License, or (at your option) any later
 * version.
 *
 * Peruggia is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Peruggia; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
?>

<html>
<head>
<title>Peruggia Installer</title>
<link rel=stylesheet href=style.css>
</head>
<body>
<br>
<table align=center>
<tr>
<td valign=top>
<fieldset style=width:300;>
<legend><b>Setup</b></legend>
<b>

<?php

include("conf.php");

$conx = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if(!$conx){
  echo "<font color=red>[-] Connect to MySQL</font><br>";
  echo mysqli_error($conx);
  $error = 1;
}else{
  echo "<font color=green>[+] Connect to MySQL</font><br>";
}

if(!mysqli_select_db($conx, $mysql_db)){
  if(!mysqli_query($conx, "CREATE DATABASE $mysql_db")){
    echo "<font color=red>[-] Create database</font><br>";
    echo mysqli_error($conx);
    $error = 1;
  }else{
    echo "<font color=green>[+] Create database</font><br>";
    mysqli_select_db($conx, $mysql_db);
  }
}else{
  echo "<font color=green>[+] Create database (exists)</font><br>";
  mysqli_select_db($conx, $mysql_db);
}

$create_table_users = mysqli_query($conx, "
CREATE TABLE users (
ID MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(60),
password VARCHAR(60)
)
");

$create_table_picdata = mysqli_query($conx, "
CREATE TABLE picdata (
ID MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
pic VARCHAR(60),
comments VARCHAR(1000),
uploader VARCHAR(1000)
)
");

if(!mysqli_num_rows(mysqli_query($conx, "SHOW TABLES LIKE 'users'"))){
  if(!($create_table_users) || !($create_table_picdata)){
    echo "<font color=red>[-] Create table</font><br>";
    echo mysqli_error($conx);
    $error = 1;
  }else{
    echo "<font color=green>[+] Create table</font><br>";
  }
}else{
  echo "<font color=green>[+] Create table (exists)</font><br>";
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$populate = false;
$i=0;
$handle = fopen("users.txt", "r");
if ($handle) {
	while (($line = fgets($handle)) !== false) {
    if ($i==0) {
      $pass = 'password';
    }
    else {
      $pass = generateRandomString(16);
    }
		$line = rtrim($line);
		mysqli_query($conx, "INSERT INTO users (username, password) VALUES ('$line', '$pass')");
    $i++;
	}
	fclose($handle);
	$populate = true;
}

if(!$populate) {
	echo "<font color=red>[-] Populate users</font><br>";
	echo mysqli_error($conx);
	$error = 1;
} else {
	echo "<font color=green>[+] Populate users</font><br>";
}


$populate = mysqli_query($conx, "
INSERT INTO picdata (pic,uploader)
VALUES ('lolhax.jpg', 'Peruggia')
");

if(!$populate){
  echo "<font color=red>[-] Populate gallery</font><br>";
  echo mysqli_error($conx);
  $error = 1;
}else{
  echo "<font color=green>[+] Populate gallery</font><br>";
}

if(isset($error)){
  echo "<font color=red>Error!</font><br>";
  echo mysqli_error($conx);
}else{
  echo "<font color=green>Success!</font><br>";
}

mysqli_close($conx);

echo "<br><a href=index.php><b>Main Page</b></a><br>";
echo "<a href=index.php?action=login><b>Log in</b></a>";

?>

</b>
</td>
<td valign=top>
<fieldset style=width:300;>
<legend><b>Information</b></legend>
There is no more default user/password, please check the database for a usable user/pass combination!
Please delete this installer once it has completed successfuly.  Not doing so may leave undesired vulnerabilities.<br>
<br>
<b>Happy Hacking!</b>
</fieldset>
</td>
</tr>
</table>
</body>
</html>
</html>
