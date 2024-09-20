<?php

require_once('conf.inc.php');
require_once('auth.inc.php');

foreach ($data->categories as $category) {
  $category->current = 0;
  foreach ($data->brought as $brought_object) {
    if ($brought_object->category == $category->key) {
      $category->current = $category->current + 1;
    }
  }

  if ($category->target) {
    $category_target_percentage = $category->current / (double)$category->target;
    $category->target_color = 'is-target-color-bad';
    if ($category_target_percentage >= $config->percentage_okay) {
      $category->target_color = 'is-target-color-okay';
    }
    if ($category_target_percentage >= $config->percentage_good) {
      $category->target_color = 'is-target-color-good';
    }
  }
}

?><!DOCTYPE html>
<html data-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $config->page_title; ?></title>
    <link rel="stylesheet" href="css/bulma.min.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico"/>
    <script src="js/ibringit.js"></script>
    <style>
      .tag
      {
        font-size: xx-small;
        vertical-align: text-bottom;
      }
      .title>.tag
      {
        font-size: 1rem;
        font-weight: 400;
        margin-bottom: 2px;
        vertical-align: text-bottom;
      }
      .card-header-title>.tag
      {
        font-weight: 400;
        margin-bottom: -3px;
      }
      .is-top-color
      {
        background-color: <?php echo $config->color_top; ?>;
      }
      .is-image-color
      {
        background-color: <?php echo $config->color_image; ?>;
      }
      .is-info-color
      {
        background-color: <?php echo $config->color_info; ?>;
      }
      .is-count-color
      {
        background-color: <?php echo $config->color_count; ?>;
      }
      .is-target-color-good
      {
        background-color: <?php echo $config->color_target_good; ?>;
      }
      .is-target-color-okay
      {
        background-color: <?php echo $config->color_target_okay; ?>;
      }
      .is-target-color-bad
      {
        background-color: <?php echo $config->color_target_bad; ?>;
      }
      .is-name-color
      {
        background-color: <?php echo $config->color_name; ?>;
      }
      .is-new-color
      {
        background-color: <?php echo $config->color_new; ?>;
      }
      .is-bottom-color
      {
        background-color: <?php echo $config->color_bottom; ?>;
      }
      .is-bottom-info-color
      {
        background-color: <?php echo $config->color_bottom_info; ?>;
      }
    </style>
  </head>
  <body>
  <div class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title"></p>
        <button class="delete" aria-label="close"></button>
      </header>
      <section class="modal-card-body">
        <div class="field">
          <div class="control">
            <input id="open_free_name_input" class="input" type="text" placeholder="<?php echo $config->open_free_name_input_placeholder; ?>">
            <p class="help"><?php echo $config->open_free_name_input_info; ?></p>
          </div>
        </div>
      </section>
      <footer class="modal-card-foot">
        <div class="buttons">
          <button id="open_free_accept" class="button is-success" disabled><?php echo $config->bringit_text; ?></button>
          <button class="button"><?php echo $config->open_free_cancel; ?></button>
        </div>
      </footer>
    </div>
  </div>
  <section class="hero is-top-color">
    <div class="hero-body">
      <div class="container" style="text-align: center;">
<?php
if ($config->image) {
?>
        <figure class="image is-square" style="width: 368px; margin: 30px auto;">
          <img class="is-rounded is-image-color" style="padding: 20px;" src="<?php echo $config->image; ?>">
        </figure>
<?php
}
?>
        <h1 class="title"><?php echo $config->title; ?></h1>
        <p class="subtitle"><?php echo $config->subtitle; ?></p>
        <p><?php echo $config->text; ?></p>
      </div>
    </div>
  </section>
<?php
if ($config->info) {
?>
  <section class="section">
    <div class="container">
    <div class="notification is-info-color">
      <p><?php echo $config->info; ?></p>
    </div>
    </div>
  </section>
<?php
}
?>
<?php
foreach ($data->categories as $category) {
  $category_remove_string = '';
  $category_target_decrement_string = '';
  $category_target_increment_string = '';
  if (Authentication::HasAccess()) {
    $category_remove_string = '<button class="delete is-small remove-category" data-link="admin-token=' . Authentication::GetToken() . '&key=' . urlencode($category->key) . '"></button> ';
    $category_target_decrement_string = ' <button class="is-small button category-target-decrement" data-link="admin-token=' . Authentication::GetToken() . '&key=' . urlencode($category->key) . '">-1</button>';
    $category_target_increment_string = ' <button class="is-small button category-target-increment" data-link="admin-token=' . Authentication::GetToken() . '&key=' . urlencode($category->key) . '">+1</button>';
  }
?>
  <section class="section">
    <div class="container">
<?php
  $category_target_string = '';
  if ($category->target) {
    $category_target_string = '&nbsp;<span class="tag ' . $category->target_color . '">' . $category->current . ' / ' . $category->target . '</span>';
  }
?>
      <h2 class="title"><?php echo $category_remove_string . $category->title . $category_target_string . $category_target_decrement_string . $category_target_increment_string; ?></h2>
      <table class="table is-hoverable is-fullwidth">
        <colgroup>
          <col span="1" style="width: 91.67%" />
          <col span="1" style="width:  8.33%" />
        </colgroup>
        <tbody>
<?php
  foreach ($data->open as $open_object) {
    if ($open_object->category == $category->key) {
      $open_object_count_string = '';
      if ($open_object->count != 0 && $open_object->count != 1) {
        $open_object_count_string = $open_object->count < 0
          ? ' <span class="tag is-count-color">&infin;</span>'
          : ' <span class="tag is-count-color">' . $open_object->count . '</span>';
      }
      $open_object_remove_string = '';
      if (Authentication::HasAccess()) {
        $open_object_remove_string = '<button class="delete is-small remove-object" data-link="admin-token=' . Authentication::GetToken() . '&object=' . urlencode($open_object->object) . '&category=' . urlencode($open_object->category) . '"></button> ';
      }
?>
          <tr><td><?php echo $open_object_remove_string; ?><?php echo $open_object->object; ?><?php echo $open_object_count_string ?></td><td><button class="button is-small bring-it" data-name="<?php echo $open_object->object ?>"><?php echo $config->bringit_text; ?></button></td></tr>
<?php
    }
  }
?>
          <tr><td><input id="category-<?php echo $category->key; ?>" class="input is-small" type="text" placeholder="<?php echo $config->open_free_object_input_placeholder; ?>"></td><td><button class="button is-small bring-it" data-name="category-<?php echo $category->key; ?>"><?php echo $config->bringit_text; ?></button></td></tr>
<?php
  if (Authentication::HasAccess()) {
    $url = 'admin-token=' . Authentication::GetToken();
?>
          <tr><td><input id="add-open-<?php echo $category->key; ?>-object" class="input is-small" type="text" placeholder="Object" style="width:50%;"><input id="add-open-<?php echo $category->key; ?>-count" class="input is-small" type="text" placeholder="Count" style="width:50%;"></td><td><button class="button is-small add-open" data-name="add-open-<?php echo $category->key; ?>" data-link="<?php echo $url; ?>">Add</button></td></tr>
<?php
  }
?>
        </tbody>
      </table>
    </div>
  </section>
<?php
}
if (Authentication::HasAccess()) {
  $url = 'admin-token=' . Authentication::GetToken();
?>
  <section class="section">
    <div class="container">
      <table class="table is-hoverable is-fullwidth">
        <colgroup>
          <col span="1" style="width: 91.67%" />
          <col span="1" style="width:  8.33%" />
        </colgroup>
        <tbody>
          <tr><td><input id="add-category-key" class="input is-small" type="text" placeholder="Key" style="width:50%;"><input id="add-category-title" class="input is-small" type="text" placeholder="Title" style="width:50%;"></td><td><button class="button is-small add-category" data-link="<?php echo $url; ?>">Add Category</button></td></tr>
        </tbody>
      </table>
    </div>
  </section>
<?php
  }
?>
  <section class="hero is-bottom-color">
    <div class="hero-body">
      <h2 class="title"><?php echo $config->brought_title; ?></h2>
<?php
if ($config->brought_info) {
?>
      <div class="notification is-bottom-info-color" style="margin-bottom: 1.5rem;">
        <p><?php echo $config->brought_info; ?></p>
      </div>
<?php
}
?>
      <div class="grid is-col-min-9">
<?php
foreach ($data->categories as $category) {
?>
        <div class="cell">
          <div class="card">
            <header class="card-header">
<?php
  $category_target_string = '';
  if ($category->target) {
    $category_target_string = '&nbsp;<span class="tag ' . $category->target_color . '">' . $category->current . ' / ' . $category->target . '</span>';
  }
?>
              <p class="card-header-title"><?php echo $category->title . $category_target_string; ?></p>
            </header>
            <div class="card-content">
              <div class="content">
<?php
  foreach ($data->brought as $brought_object) {
    if ($brought_object->category == $category->key) {
      $brought_object_new_string = '';
      if ($brought_object->new) {
        $brought_object_new_string = Authentication::HasAccess()
          ? ' <span class="tag is-new-color">' . $config->new . ' <button class="delete is-small remove-new-tag" data-link="admin-token=' . Authentication::GetToken() . '&object=' . urlencode($brought_object->object) . '&name=' . urlencode($brought_object->name) . '"></button></span>'
          : ' <span class="tag is-new-color">' . $config->new . '</span>';
      }
      $brought_object_remove_string = '';
      if (Authentication::HasAccess()) {
        $brought_object_remove_string = '<button class="delete is-small remove-brought" data-link="admin-token=' . Authentication::GetToken() . '&object=' . urlencode($brought_object->object) . '&name=' . urlencode($brought_object->name) . '"></button> ';
      }
?>
                <p><?php echo $brought_object_remove_string; ?><?php echo $brought_object->object; ?> <span class="tag is-name-color"><?php echo $brought_object->name; ?></span><?php echo $brought_object_new_string; ?></p>
<?php
    }
  }
?>
              </div>
            </div>
          </div>
        </div>
<?php
}
?>
      </div>
    </div>
  </section>
  </body>
</html>
