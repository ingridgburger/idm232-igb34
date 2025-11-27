<?php
require 'db.php';

$search = '';
$category = '';

if (isset($_GET['search'])) {
  $search = trim($_GET['search']);
}

if (isset($_GET['category'])) {
  $category = trim($_GET['category']);
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
          `Main IMG`
        FROM recipes
        WHERE 1=1";

if ($search !== '') {
  $like = '%' . $search . '%';
  $sql .= " AND (
              `Title` LIKE '$like' 
              OR `Subtitle` LIKE '$like' 
              OR `Description` LIKE '$like'
            )";
}

if ($category !== '') {
  $sql .= " AND `Protein` = '$category'";
}

$result = $connection->query($sql);

if (!$result) {
  die('Query error: ' . $connection->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Cookbook</title>
  <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
<header class="page-header">
  <div class="header-inner">

    <div class="header-top-row">
      <h1>Welcome to My Cookbook</h1>
      <a href="help.php" class="help-link"></a>
    </div>

    <p class="header-subtitle">Discover amazing recipes for every meal.</p>

    <section class="search-filter">
      <form action="index.php" method="get">

        <input
          type="text"
          name="search"
          placeholder="Search recipes..."
          class="search-bar"
          value="<?php echo $search; ?>"
        >

        <select name="category" class="filter-dropdown">
          <option value="">All Proteins</option>

          <?php
            $proteins = [
              "Beef",
              "Chicken",
              "Fish",
              "Pork",
              "Steak",
              "Turkey",
              "Vegetarian"
            ];

            foreach ($proteins as $p) {
              $selected = ($category === $p) ? "selected" : "";
              echo "<option value=\"$p\" $selected>$p</option>";
            }
          ?>
        </select>

        <button type="submit" class="button">Search</button>
      </form>
    </section>

  </div>
</header>


<main class="main-content">
  <?php if ($result->num_rows > 0) { ?>
    <section class="grid-container">
      <?php while ($row = $result->fetch_assoc()) { ?>

        <article class="recipe-card">

          <div class="recipe-card-image-wrapper">
            <img
              src="images/<?php echo $row['Main IMG']; ?>"
              alt="<?php echo $row['Title']; ?>"
            >
            <div class="cook-time">
              <?php echo $row['Cook Time']; ?>
            </div>
          </div>

          <div class="recipe-card-body">
            <h2><?php echo $row['Title']; ?></h2>

            <?php if ($row['Subtitle'] !== '') { ?>
              <h4><?php echo $row['Subtitle']; ?></h4>
            <?php } ?>

            <div class="recipe-card-meta">
              <?php if ($row['Servings'] !== '') { ?>
                <span><?php echo $row['Servings']; ?> servings</span>
              <?php } ?>

              <?php if ($row['Cal/Serving'] !== '') { ?>
                <span><?php echo $row['Cal/Serving']; ?> cal</span>
              <?php } ?>

              <?php if ($row['Protein'] !== '') { ?>
                <span><?php echo $row['Protein']; ?></span>
              <?php } ?>
            </div>

            <a
              href="recipe.php?id=<?php echo $row['id']; ?>"
              class="button card-button"
            >
              View Recipe
            </a>
          </div>

        </article>

      <?php } ?>
    </section>

  <?php } else { ?>
    <section class="grid-container">
      <p>No recipes found.</p>
    </section>
  <?php } ?>

</main>

<footer>
  <p>© 2025 My Cookbook. All rights reserved.</p>
</footer>

<?php
$result->free();
$connection->close();
?>

</body>
</html>