<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user'])) header('Location: login.php');
$id = isset($_GET['id'])? (int)$_GET['id']:0;
if($id) {
    $stmt = $conn->prepare('DELETE FROM kategori WHERE id=?');
    $stmt->bind_param('i',$id);
    $stmt->execute();
}
header('Location: kategori.php');
exit;
