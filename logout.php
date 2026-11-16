<?php
session_start();
$_SESSION=[];
session_destroy();
header('Location: /fitlife/index.php');
exit;
