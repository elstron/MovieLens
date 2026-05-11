<?php
$mainContent = function() use($title, $description, $movie) { 
    include __DIR__ . '/../../ui/components/movies/details/Hero.php';
};
include __DIR__ . '/../../ui/Layouts/HomeLayout.php';