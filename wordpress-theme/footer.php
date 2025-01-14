
<?php
$footer_id = get_mount_point('footer', 'react-footer');
?>

    </main> <!-- Closing the <main> from header.php -->

    <!-- Use <footer> as the React mount point directly (or wrap a <div> inside) -->
    <footer role="contentinfo" class="site-footer">
        <div id="<?php echo esc_attr($footer_id); ?>"></div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>