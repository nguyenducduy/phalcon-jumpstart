<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/**
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 **/

return array(
    'jquery' => array(
        '../plugins/jquery/jquery.js',
        '../plugins/jquery/jquery-migrate.min.js',
    ),
    'jsAdmin' => array(
        '../js/admin/modernizr.min.js',
        '../js/admin/pace.min.js',
        '../js/admin/retina.min.js',
        '../js/admin/custom.js',
        '../js/admin/toastr.min.js',
        '../js/admin/dropzone.js',
        '../js/admin/bootstrap-markdown-editor.js',
        '../js/admin/main.js',
	),
    'jsCommon' => array(
        '../js/common/pace.min.js',
        '../js/common/modernizr.custom.js',
        '../js/common/detectizr.min.js',
        '../js/common/jquery.easing.1.3.js',
        '../js/common/velocity.min.js',
        '../js/common/smoothscroll.js',
        '../js/common/waves.min.js',
        '../js/common/form-plugins.js',
        '../js/common/jquery.mCustomScrollbar.min.js',
        '../js/common/isotope.pkgd.min.js',
        '../js/common/bootstrap-slider.min.js',
        '../js/common/main.js',
    ),
	'cssCommon' => array(
        '../css/common/style.css',
        '../css/common/tomorrow.css',
    ),
	'cssAdmin' => array(
        '../css/admin/bootstrap-override.css',
        '../plugins/fontawesome/font-awesome.min.css',
        '../css/admin/pace.css',
        '../css/admin/chain.css',
        '../css/admin/toastr.min.css',
        '../css/admin/dropzone.css',
        '../css/admin/spf.css',
        '../css/admin/bootstrap-markdown-editor.css',
        '../css/admin/mystyle.css',
	),
);
