<?php
session_start();
if(!isset($_SESSION['user_id']))
{
	Header("Location:../common/login.php");
}
?>