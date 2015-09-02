<?php
file_put_contents('./cache/debug.txt', var_export($_POST,1).var_export($_GET,1));
?>