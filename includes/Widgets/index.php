<?php

namespace UVTADDON\Widgets;

use UVTADDON\Widgets\Uvt_Widget;

/**
 * Register widgets
 *
 * @return void
 */
function register_widgets() {

    register_widget( Uvt_Widget::class );
}

add_action( 'widgets_init', __NAMESPACE__ . '\\register_widgets' );
