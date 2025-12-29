<?php 
/* Template Name: Custom Logout Page */ 

// 1. 开启 Session (为了能找到要销毁的 Session)
if (!session_id()) { session_start(); }

// 2. 清空所有 Session 变量
$_SESSION = array();

// 3. 销毁 Session
session_destroy();

// 4. 跳转回登录页面 (这里假设你的登录页 ID 是 77)
// 这样用户退出后，立马就能看到登录框，体验很好
$login_page_id = 77; 
wp_redirect(home_url('/?page_id=' . $login_page_id)); 
exit;
?>