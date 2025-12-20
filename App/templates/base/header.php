<!doctype html>
<html lang="<?php echo Session::has('language') ? Session::get('language') : DEFAULT_LANGUAGE; ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="generator" content="Kristal Framework">

        <?php if (DISCOURAGE_SEARCH_ENGINES): ?>
            <meta name="robots" content="noindex, nofollow">
        <?php else: ?>
            <meta name="robots" content="index, follow">
        <?php endif; ?>

        <!-- Page metadata -->
        <?php if (isset($kristal_metadata[$page]) || isset($kristal_metadata["*"])): ?>
            <?php foreach (isset($kristal_metadata[$page]) ? $kristal_metadata[$page] : $kristal_metadata["*"] as $key => $value): ?>
                <?php if (strpos($key, "og:") === 0): ?>
                    <meta property="<?php echo esc_html($key); ?>" content="<?php echo esc_html($value); ?>">
                <?php else: ?>
                    <meta name="<?php echo esc_html($key); ?>" content="<?php echo esc_html($value); ?>">
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Canonical URL -->
        <link rel="canonical" href="<?php echo URL_BASE; ?>">
        
        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
        <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="<?php echo css(Session::has("theme") ? Session::get("theme") : DEFAULT_THEME); ?>">
        
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="<?php echo js("Core/animator.js.js"); ?>"></script>
        <script src="<?php echo js("Core/form.js"); ?>"></script>
        <script src="<?php echo js("Core/tooltips.js.js"); ?>"></script>
        <script src="<?php echo js("Core/translator.js"); ?>"></script>

        <!-- Page title -->
        <?php if (!empty($kristal_metadata[$page]["title"])): ?>
            <title><?php echo esc_html($kristal_metadata[$page]["title"]); ?></title>
        <?php elseif (!empty($kristal_metadata["*"]["title"])): ?>
            <title><?php echo esc_html($kristal_metadata["*"]["title"]); ?></title>
        <?php else: ?>
            <title><?php echo URL_BASE . $page; ?></title>
        <?php endif; ?>

        <!-- Website icon -->
        <link rel="icon" type="image/gif" href="<?php echo webp("kristal_framework_alt_icon.png"); ?>" />

    </head>
    
    <body>
        <main>
