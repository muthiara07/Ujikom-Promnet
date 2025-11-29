<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user'])) header('Location: login.php');
$id = isset($_GET['id'])? (int)$_GET['id']:0;
if($id) {
    $stmt = $conn->prepare('SELECT sampul FROM buku WHERE id=? LIMIT 1');
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $res = $stmt->get_result();
    if($r=$res->fetch_assoc()) {
        if($r['sampul'] && file_exists('uploads/'.$r['sampul'])) unlink('uploads/'.$r['sampul']);
    }
    $stmt2 = $conn->prepare('DELETE FROM buku WHERE id=?');
    $stmt2->bind_param('i',$id);
    $stmt2->execute();
}
header('Location: index.php');
exit;
