<html>

<body>
<?php
    include_once 'ofc-library/open_flash_chart_object.php';
    open_flash_chart_object( 500, 250, 'http://'. $_SERVER['SERVER_NAME'] .'/compte/chart-data.php', false );
?>
</body>

</html>
