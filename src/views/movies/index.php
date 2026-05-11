<?php
$mainContent = function() use($movies) { ?>
    <?php include __DIR__ . '/../../ui/components/movies/movies.php'; ?>
<?php };
include __DIR__ . '/../../ui/Layouts/HomeLayout.php';