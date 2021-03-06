<?php
session_name('hydrid');
session_start();
require '../../../connect.php';

require '../../../config.php';

require '../../../backend/user/auth/userIsLoggedIn.php';

if (!isset($_SESSION['on_duty'])) {
	header('Location: ../../../../' . $url['leo'] . '?v=nosession');
	exit();
}

// Page PHP
$sql             = "SELECT * FROM on_duty";
$stmt            = $pdo->prepare($sql);
$stmt->execute();
$ondutyDBcall = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($ondutyDBcall as $on_duty ) {
  echo "<table style='width:100%'>
  <tr>
    <th>Unit</th>
    <th>Status</th>
  </tr>";
  echo "<tr>";
  echo "<td>" . $on_duty['name'] . "</td>";
  echo "<td>" . $on_duty['status'] . "</td>";
  echo "</tr>";
  echo "</table>";
}
