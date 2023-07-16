<?php

$updaterBase = 'https://projects.bluewindlab.net/wpplugin/zipped/plugins/';
$pluginRemoteUpdater = $updaterBase . 'bpvm/notifier_uvt.php';
new WpAutoUpdater(BPVM_UVT_ADDON_CURRENT_VERSION, $pluginRemoteUpdater, BPVM_UVT_ADDON_UPDATER_SLUG);
