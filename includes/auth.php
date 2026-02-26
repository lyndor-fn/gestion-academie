<?php
require_once __DIR__.'/config.php';
if(session_status() === PHP_SESSION_NONE) session_start();

function require_login(){
    if(empty($_SESSION['user'])){ header('Location: login.php'); exit; }
}

function attempt_login($username, $password){
    global $admin_user, $admin_pass;
    if(empty($username) || empty($password)) return false;
    if($username === $admin_user && $password === $admin_pass){
        $_SESSION['user'] = $username;
        $_SESSION['authenticated'] = true;
        return true;
    }
    return false;
}

function is_logged_in(){
    return !empty($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

function get_authenticated_user(){
    return $_SESSION['user'] ?? null;
}
