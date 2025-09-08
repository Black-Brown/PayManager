<?php
require('../config/database.php');

echo "welcome to Jheinel's house";

$db = new database();
$this->conn = $db->getDB();