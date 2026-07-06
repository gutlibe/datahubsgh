<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? $appName . ' - Data Delivery & Purchase Guide'; ?></title>
    <meta name="description" content="Learn how to buy data and check your order status on <?php echo $appName; ?>. Step-by-step guide to getting your MTN, AT, and Telecel data delivered in minutes.">
    <link rel="icon" href="<?php echo rtrim(APP_URL, '/'); ?>/public/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body class="bg-gray-100">
    <?php view('components/header'); ?>
    <main class="container mx-auto mt-10">
        <?php require $content; ?>
    </main>
    <script src="<?php echo asset('js/main.js'); ?>"></script>
</body>
</html>
