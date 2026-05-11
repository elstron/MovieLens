<?php
$mainContent = function() use ($title, $lastMovie) {
  include __DIR__ . '/../ui/components/home/Hero.php';
 };
include __DIR__ . '/../ui/Layouts/HomeLayout.php';