<div class="kristal-debug-block">
    <?php if (!empty($name)): ?>
        <p class="kristal-debug-title"><strong>Debugging</strong>: $<?php echo esc_html($name); ?></p>
    <?php endif; ?>
    <div class="kristal-debug-content">
        <pre class="kristal-debug-variable"><?php echo esc_html(var_export($value, true), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?></pre>
    </div>
</div>

<style>
    .kristal-debug-block {
        background-color: #f1f1f1 !important;
        border: 2px solid #007700 !important;
        margin: 12px !important;
        padding: 20px !important;
    }
    
    .kristal-debug-title {
        font-family: Helvetica, Arial, sans-serif !important;
        color: black !important;
        font-size: 16px;
        line-height: 24px !important;
    }

    .kristal-debug-variable {
        font-family: monospace !important;
        color: black !important;
        font-size: 16px;
        line-height: 24px !important;
        white-space: pre-wrap !important;
    }
</style>
