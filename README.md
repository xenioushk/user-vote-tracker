## User Vote Tracker - Pro Voting Manager Addon

This addon performs the task of diligently monitoring and counting votes on each post. It then compiles these vote records into user-friendly lists readily accessible on the individual user profile pages. This feature simplifies reviewing one's voting history and encourages user engagement with post-voting.

![Demo of User Vote Tracker - Pro Voting Manager Addon](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/img/overview/report_interface.jpg)

[Demo](https://projects.bluewindlab.net/wpplugin/bpvm/) | [Download](https://bluewindlab.net/product/user-vote-tracker-addon/) | [Documentation](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/index.html)

## Addon requirements

You need to install [BWL Pro Voting Manager WordPress plugin](https://1.envato.market/bpvm-wp) to use the addon.

You need at least WordPress version 4.8+ installed for this plugin to work properly. It is strongly recommended that you always use the latest stable version of WordPress to ensure all known bugs and security issues are fixed.

## Technical requirements

- WordPress 5.6 or greater.
- PHP version 7.4 or greater.
- MySQL version 5.5.51 or greater.

## Installation

1. Go to plugins section in your WordPress admin panel and click `Add New` to install plugin.

   ![Add new plugin](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/img/installation/1.jpg)

2. Now, upload the `user-vote-tracker.zip` file.

   ![Upload the addon](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/img/installation/2.jpg)

3. Once plugin successfully uploaded in your server you will get an message to activate it. Click on `Activate Plugin` Link and plugin will be ready to use.

4. After activating plugins, you will redirect in plugins section of wp-admin panel and show new installed plugins information in there.

   ![User Vote Tracker - Pro Voting Manager Addon](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/img/installation/3.png)

## How to use

- Once you completed installation process, plugin will automatically added a "My Votes" submenu in USER menu section. In `My Votes` page you will able to access all of your previously voted information. You can filter them by post type/ date / vote types (liked/disliked).

  ![User Vote Tracker - Pro Voting Manager Addon](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/img/operate/02_user_vote_tracker_submenu.jpg)

## Shortcodes

You can also display voting report page any where of your site using shrotcode. Here goes list of shortcode-

```bash
[uvt_front filter="1" limit="5" pagination="1"]
```

#### Parameters

```
//  filter= 1 or 2 . 1= Liked, 2= Disliked
//  limit = 5 ; No of items you like to show.
// pagination = 1 or 0 . 1= Show, 0=Hide.
```

### PHP Example

```php
echo do_shortcode('[uvt_front filter="1" limit="5" pagination="1"]');
```

## Widget

- Addon comes with built-in widget to display top voted products. Go to WordPress `Admin Panel>Appearance> widgets section`. There you will get the
  **User Vote Tracker Widget**.

  ![User Vote Tracker - Pro Voting Manager Addon](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/img/operate/03_uvt_vote_widget_1.jpg)

- Configure it according to your need.

  ![User Vote Tracker - Pro Voting Manager Addon](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/img/operate/04_uvt_vote_widget_2.jpg)

**Widget output**

![User Vote Tracker - Pro Voting Manager Addon](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/img/operate/05_uvt_vote_widget_3_result.jpg)

## Translation

- The addon text-domain is `bpvm_uvt`
- Inside of addon `languages` folder you will get a file named `user-vote-tracker.pot` file. To edit this file you need to install "poedit" software in your computer.
- Open that `user-vote-tracker.pot'`, and start adding translated texts.
- Suppose you want to translate plugin in to "German" language. So,".po" file name will be `bpvm_uvt-de_DE.po`. That's all.

## Change log

- [Change log](https://xenioushk.github.io/docs-plugins-addon/bpvm-addon/uvta/index.html#changelog)

### Acknowledgement

- [bluewindlab.net](https://bluewindlab.net)
- [BWL Pro Voting Manager WordPress plugin](https://1.envato.market/bpvm-wp)
