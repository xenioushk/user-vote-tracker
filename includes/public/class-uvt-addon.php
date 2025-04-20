<?php

class BPVM_UVT {


    const VERSION = BPVM_UVT_ADDON_CURRENT_VERSION;

    protected $plugin_slug     = 'bpvm-uvt';
    protected static $instance = null;

    private function __construct() {

        if ( class_exists( 'BPVMWP\\Init' ) && BPVM_UVT_PARENT_PLUGIN_INSTALLED_VERSION >= BPVM_UVT_PARENT_PLUGIN_REQUIRED_VERSION ) {

            $this->include_files();
        }
    }
    public function include_files() {
        include_once BPVM_UVT_PATH . 'includes/widgets/user-vote-tracker-widget.php';
    }
}
