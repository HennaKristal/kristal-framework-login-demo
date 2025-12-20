<?php
/**
 * Available variables:
 *  - $atts['request']
 *  - $atts['languages']
 */
?>

<ul class="language-menu navbar-nav justify-content-end">
    <?php foreach ($atts['languages'] as $language): ?>
        <li class="nav-item">
            <form method='post'>
                <?php CSRF::create($atts['request'] . "_" . $language, "change_language"); ?>
                <button type='submit' name='language' value='<?php echo $language; ?>' class='btn btn-link p-0 border-0 bg-transparent'>
                    <img src="<?php echo webp("flags/$language.jpg"); ?>" class="change-language <?php if (Session::get("language") === $language) echo "active"; ?>" />
                </button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
