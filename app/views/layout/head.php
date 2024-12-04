<!-- head.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? ''; ?>">
    <title><?= isset($metaTitle) ? htmlspecialchars($metaTitle) : 'Default Title' ?></title>

    <meta name="description" content="<?= isset($metaTitle) ? htmlspecialchars($metaTitle) : 'Default meta description' ?>">
    <meta name="page-type" content="<?= htmlspecialchars($page ?? '') ?>">
    <!-- Icons -->
    <link rel="icon" href="<?= asset('assets/brand/LogoIdea.svg'); ?>" type="image/x-icon">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://use.typekit.net/qcq3ahl.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<?php
// echo "Page Title: " . (isset($pageTitle) ? $pageTitle : 'Not Set') . "<br>";
// echo "Meta Title: " . (isset($metaTitle) ? $metaTitle : 'Not Set') . "<br>";
?>