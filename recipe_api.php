<?php
require 'db.php';

header('Content-Type: application/json');

$id = 0;
if (isset($_GET['id'])) {
  $id = (int) $_GET['id'];
}

$sql = "SELECT 
          id,
          `Title`,
          `Subtitle`,
          `Description`,
          `Cook Time`,
          `Servings`,
          `Cal/Serving`,
          `Protein`,
          `Main IMG`,
          `All Ingredients`,
          `All Steps`
        FROM recipes
        WHERE id = $id
        LIMIT 1";

$result = $connection->query($sql);

$recipe = null;

if ($result && $result->num_rows > 0) {
  $recipe = $result->fetch_assoc();
}

echo json_encode($recipe);