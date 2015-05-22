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
        '../js/admin/main.js',
	),
    'jsCommon' => array(
        '../js/common/materialize.min.js',
    ),
	'cssCommon' => array(
        '',
    ),
	'cssAdmin' => array(
        '../css/admin/bootstrap-override.css',
        '../plugins/fontawesome/font-awesome.min.css',
        '../css/admin/pace.css',
        '../css/admin/chain.css',
        '../css/admin/toastr.min.css',
        '../css/admin/dropzone.css',
        '../css/admin/spf.css',
        '../css/admin/mystyle.css',
	),
);
