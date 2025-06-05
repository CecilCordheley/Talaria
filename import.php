<?php

header('Content-Type: application/json');

if (!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['success' => false, 'error' => 'Fichier non reçu ou erreur à l\'upload']);
  exit;
}

$filename = $_FILES['csvFile']['tmp_name'];
$rows = array_map('str_getcsv', file($filename));
$header = array_shift($rows);

if (!$header) {
  echo json_encode(['success' => false, 'error' => 'En-têtes CSV manquantes']);
  exit;
}

$agents = array_map(fn($r) => array_combine($header, $r), $rows);
echo json_encode(['success' => true, 'agents' => $agents]);
