<?php // demo/jquery_progress_bar_server.php
error_reporting(E_ALL);

// SESSION ALLOWS US TO HAVE STATIC VARIABLES
session_start();

// IF THIS IS THE FIRST STEP, SET THE PROGRESS BAR TO ZERO
if (empty($_SESSION['progress']))
{
    $_SESSION['progress'] = 0;
}

// INCREMENT THE PROGRESS COUNTER
$advance = 10;
$advance = rand(5,10);
$_SESSION['progress'] += $advance;


// IF THIS IS THE LAST STEP, ELIMINATE THE SESSION VARIABLE
if ($_SESSION['progress'] > 99)
{
    unset($_SESSION['progress']);
    session_write_close();
    die('100');
}

// RETURN THE PROGRESS
echo $_SESSION['progress'];

?>