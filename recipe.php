<?php
require 'db.php';

$recipe = null;
$ingredients = array();
$steps = array();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = (int) $_GET['id'];

    $stmt = $connection->prepare(
        "SELECT 
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
            `Step IMGs`,
            `Step Title #1`, `Step Desc #1`,
            `Step Title #2`, `Step Desc #2`,
            `Step Title #3`, `Step Desc #3`,
            `Step Title #4`, `Step Desc #4`,
            `Step Title #5`, `Step Desc #5`,
            `Step Title #6`, `Step Desc #6`
         FROM recipes
         WHERE id = ?"
    );

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();

        if (!empty($recipe['All Ingredients'])) {
            $ingredients_raw = explode('*', $recipe['All Ingredients']);
            $ingredients = array_filter(array_map('trim', $ingredients_raw));
        }

        $step_images = array();
        if (!empty($recipe['Step IMGs'])) {
            $step_images_raw = explode('*', $recipe['Step IMGs']);
            $step_images = array_values(array_filter(array_map('trim', $step_images_raw)));
        }

        for ($i = 1; $i <= 6; $i++) {
            $titleKey = 'Step Title #' . $i;
            $descKey  = 'Step Desc #' . $i;

            $title = isset($recipe[$titleKey]) ? trim($recipe[$titleKey]) : '';
            $desc  = isset($recipe[$descKey])  ? trim($recipe[$descKey])  : '';

            if ($title === '' && $desc === '') {
                continue;
            }

            $img = isset($step_images[$i - 1]) ? $step_images[$i - 1] : '';

            $steps[] = array(
                'title' => $title,
                'desc'  => $desc,
                'img'   => $img
            );
        }
    }

    $stmt->close();
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

<header class="recipe-header">
  <a href="index.php" class="recipe-back-link">
    <div class="recipe-header-inner">
      <span class="recipe-back-arrow">←</span>
      <span class="recipe-back-text">Return to recipes</span>
    </div>
  </a>
</header>

<?php if ($recipe) { ?>
<section class="recipe-hero">
  <img
    src="images/<?php echo $recipe['Main IMG']; ?>"
    alt="<?php echo $recipe['Title']; ?>"
    class="recipe-hero-image"
  >

  <div class="recipe-hero-overlay"></div>

  <div class="recipe-hero-text">
    <h1><?php echo $recipe['Title']; ?></h1>

    <?php if ($recipe['Subtitle'] != '') { ?>
      <h3><?php echo $recipe['Subtitle']; ?></h3>
    <?php } ?>

    <p class="recipe-meta">
      Cook time: <?php echo $recipe['Cook Time']; ?>
      <?php if ($recipe['Servings'] != '') { ?>
        | Serves: <?php echo $recipe['Servings']; ?>
      <?php } ?>
      <?php if ($recipe['Cal/Serving'] != '') { ?>
        | Calories: <?php echo $recipe['Cal/Serving']; ?>
      <?php } ?>
      <?php if ($recipe['Protein'] != '') { ?>
        | Protein: <?php echo $recipe['Protein']; ?>
      <?php } ?>
    </p>

    <a href="index.php" class="button">Back to Home</a>
  </div>
</section>

<section class="recipe-details">
  <h2>Overview</h2>
  <p><?php echo $recipe['Description']; ?></p>

  <?php if (!empty($ingredients)) { ?>
    <h2>Ingredients</h2>
    <ul class="ingredient-list">
      <?php foreach ($ingredients as $ingredient) { ?>
        <li><?php echo $ingredient; ?></li>
      <?php } ?>
    </ul>
  <?php } ?>

  <?php if (!empty($steps)) { ?>
  <h2>Instructions</h2>
  <ol class="recipe-steps">
    <?php foreach ($steps as $index => $step) { ?>
      <li class="recipe-step">

        <?php if ($step['img'] != '') { ?>
          <div class="step-image">
            <img
              src="images/<?php echo $step['img']; ?>"
              alt="Step <?php echo $index + 1; ?>"
            >
          </div>
        <?php } ?>

        <div class="step-text">
          <?php if ($step['title'] != '') { ?>
            <h3><?php echo $step['title']; ?></h3>
          <?php } ?>
          <?php if ($step['desc'] != '') { ?>
            <p><?php echo $step['desc']; ?></p>
          <?php } ?>
        </div>

      </li>
    <?php } ?>
  </ol>
<?php } ?>
</section>

<?php } else { ?>
<main class="main-content">
  <section class="intro-section">
    <h1>Recipe Not Found</h1>
    <p>We could not find the recipe you were looking for.</p>
    <a href="index.php" class="button">Back to Home</a>
  </section>
</main>
<?php } ?>

<footer>
  <p>© 2025 My Cookbook. All rights reserved.</p>
</footer>

</body>
</html>
