<?php 
include('session.php');

if(isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
    header('Location: /'); // redirect to the homepage
}

if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    echo 'Hello, ' . $_SESSION['username'];
} else {
    echo 'Hello, strager';
}