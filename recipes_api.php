<?php
require 'db.php';

header('Content-Type: application/json');

$sql = "SELECT 
          id,
          `Title`,
          `Subtitle`,
          `Cook Time`,
          `Servings`,
          `Cal/Serving`,
          `Protein`,
          `Main IMG`
        FROM recipes";

$result = $connection->query($sql);

$recipes = [];

if ($result) {
  while ($row = $result->fetch_assoc()) {
    $recipes[] = $row;
  }
}

echo json_encode($recipes);