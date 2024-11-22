<!-- head.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Default Title' ?></title>
    <meta name="description" content="<?= isset($metaTitle) ? htmlspecialchars($metaTitle) : 'Default meta description' ?>">
    <meta name="page-type" content="<?= htmlspecialchars($page ?? '') ?>">

    <link rel="icon" href="<?= asset('assets/brand/LogoIdea.svg');?>" type="image/x-icon">

    <!-- Include CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://use.typekit.net/qcq3ahl.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

</head>
<?php
// echo "Page Title: " . (isset($pageTitle) ? $pageTitle : 'Not Set') . "<br>";
// echo "Meta Title: " . (isset($metaTitle) ? $metaTitle : 'Not Set') . "<br>";
?>