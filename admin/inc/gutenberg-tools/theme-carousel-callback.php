<?php
defined( 'ABSPATH' ) or die();
/**
 * Gutenberg TOOLS REST API CALLBACK
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

//Google Maps
function callback_hupa_theme_carousel( $attributes ) {
    return apply_filters( 'gutenberg_block_hupa_carousel_render', $attributes);
}

function gutenberg_block_hupa_carousel_render_filter($attributes){
    if ($attributes ) {
        ob_start();
        ?>
        <div class="carousel-wrapper carousel-<?=$attributes['selectedCarousel']?> <?=$attributes['className']?>">
            <?php
            echo do_shortcode('[carousel id="' . $attributes['selectedCarousel'] . '"]');
            ?>
        </div>
        <?php
        return ob_get_clean();
    }
}