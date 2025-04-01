<?php

// This class displays options panel, addons, documentation links below the plugin information.

class BpvmUvtMetaInfo
{

    public function __construct()
    {
        add_filter('plugin_row_meta', [$this, 'cbAdditionalMetaLinks'], null, 2);
    }

    public function cbAdditionalMetaLinks($links, $file)
    {

        if (strpos($file, BPVM_UVT_ADDON_ROOT_FILE) !== false && is_plugin_active($file)) {

            // nt = 1 // new tab.

            $additionalLinks = [

            [
            'title' => esc_html__("My Votes Report", "bpvm_uvt"),
            'url' => get_admin_url() . 'users.php?page=bpvm-my-votes',
            ],
            [
            'title' => esc_html__("Docs", "bpvm_uvt"),
            'url' => 'https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvt/index.html',
            'nt' => 1
            ],
            [
            'title' => esc_html__("Support", "bpvm_uvt"),
            'url' => "https://codecanyon.net/item/bwl-pro-voting-manager/7616885/support",
            'nt' => 1
            ],
            [
            'title' => esc_html__("Tutorials", "bpvm_uvt"),
            'url' => BPVM_PRODUCT_YOUTUBE_PLAYLIST,
            'nt' => 1
            ]

            ];

            $new_links = [];

            foreach ($additionalLinks as $alData) {

                $newTab = isset($alData['nt']) ? 'target="_blank"' : "";
                $class = isset($alData['class']) ? 'class="' . $alData['class'] . '"' : "";

                $new_links[] =  '<a href="' . esc_url($alData['url']) . '"  ' . $newTab . '  ' . $class . '>' . $alData['title'] . '</a>';
            }

            $links = array_merge($links, $new_links);
        }

        return $links;
    }
}

new BpvmUvtMetaInfo();
