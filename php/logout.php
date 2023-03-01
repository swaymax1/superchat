<?php

require_once("utilities.php");

session_start();

destroySession();
header("location: ../login.php");