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

if(isset($_GET['del_id'])){

  if(isset($_SESSION['admin']) && ($_SESSION['admin']==1)){
    if($guard_sqli){
	  mysqli_query($conx, "UPDATE picdata SET comments = '' WHERE ID LIKE ".(int)$_GET['del_id']);
	}else{
      mysqli_query($conx, "UPDATE picdata SET comments = '' WHERE ID LIKE ".$_GET['del_id']);
	}
    header("Location: ".$peruggia_root);
  }else{
    header("Location: ".$peruggia_root);
  }

}

if(isset($_GET['postcomment'])){

  if($guard_pers_xss){
    $comment = htmlentities($_POST['comment'])."<br><br>";
  }else{
    $comment = $_POST['comment']."<br><br>";
  }
  if($guard_sqli){
    $comment = mysqli_real_escape_string($conx, $comment);
  }
  if($guard_sqli){
    $crntquery = mysqli_query($conx, "SELECT comments FROM picdata WHERE ID LIKE ".(int)$_GET['pic_id']);
  }else{
    $crntquery = mysqli_query($conx, "SELECT comments FROM picdata WHERE ID LIKE ".$_GET['pic_id']);
  }
  $crntcomm = mysqli_fetch_array($crntquery);
  $save = $crntcomm['comments'].$comment;
  if($guard_sqli){
    mysqli_query($conx, "UPDATE picdata SET comments = '".$save."' WHERE ID LIKE ".(int)$_GET['pic_id']);
  }else{
    mysqli_query($conx, "UPDATE picdata SET comments = '".$save."' WHERE ID LIKE ".$_GET['pic_id']);
  }
  header("Location: ".$peruggia_root);

}else{

?>

<div align=center>
<fieldset style=width:300;>
<legend><b>Add Comment</b></legend>
<br>
<?php

if($guard_sqli){
  $picquery = mysqli_query($conx, "SELECT * FROM picdata WHERE ID = ".(int)$_GET['pic_id']);
}else{
  $picquery = mysqli_query($conx, "SELECT * FROM picdata WHERE ID = ".$_GET['pic_id']);
}
$data = mysqli_fetch_array($picquery);

echo "<img src=images/".$data['pic']." border=1><br><br><b>Uploaded By: ".$data['uploader']."</b><br>";

?>
<br>
<form action=<?php
if($guard_refl_xss){
  echo $peruggia_root."?action=comment&pic_id=".htmlentities($_GET['pic_id'])."&postcomment=1";
}else{
  echo $peruggia_root."?action=comment&pic_id=".$_GET['pic_id']."&postcomment=1";
}
?> method=POST>
<textarea name=comment cols=50 rows=10>
</textarea><br>
<br>
<input type=submit value=Post>
</fieldset>
</div>

<?php

}

?>
