<?php
session_destroy();
ob_end_clean();
header('location: /');
?>