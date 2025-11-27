<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>No Results | My Cookbook</title>
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
    <section>
      <h1>No Recipes Found</h1>
      <p>Sorry, we couldn’t find any recipes matching your search. Try adjusting your filters or search term.</p>
      <a href="index.html" class="button">Back to Home</a>
    </section>
  </main>

  <footer>
    <p>© 2025 My Cookbook. All rights reserved.</p>
  </footer>
</body>
</html>
