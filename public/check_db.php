<?php
$db = new mysqli('localhost', 'root', '', 'sim_orders');
$res = $db->query("DESCRIBE import_log");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}
