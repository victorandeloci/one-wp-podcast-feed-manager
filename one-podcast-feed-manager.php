<?php
/**
 * Plugin Name: One Podcast Feed Manager
 * Description: Custom RSS feed for the "podcast" category
 * Version: 0.0.3
 * Author: Victor Andeloci
 * Author URI: https://github.com/victorandeloci
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom feed.
 */
function onepodcast_add_feed() {
    add_feed('podcast', 'onepodcast_render_feed');
}
add_action('init', 'onepodcast_add_feed');

/**
 * Register settings page.
 */
function onepodcast_register_admin_menu() {
    add_menu_page(
        'Podcast Feed Settings',
        'Podcast Feed',
        'manage_options',
        'onepodcast-feed-settings',
        'onepodcast_render_admin_page',
        'dashicons-microphone',
        60
    );
}
add_action('admin_menu', 'onepodcast_register_admin_menu');

/**
 * Render settings page.
 */
function onepodcast_render_admin_page() {
    ?>
    <div class="wrap">
        <h1>Podcast Feed Settings</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('onepodcast_settings_group');
                do_settings_sections('onepodcast-feed-settings');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Register feed settings.
 */
function onepodcast_register_settings() {
    register_setting('onepodcast_settings_group', 'one_podcast_title');
    register_setting('onepodcast_settings_group', 'one_podcast_description');
    register_setting('onepodcast_settings_group', 'one_podcast_author');
    register_setting('onepodcast_settings_group', 'one_podcast_cover_url');
    register_setting('onepodcast_settings_group', 'one_podcast_explicit');
    register_setting('onepodcast_settings_group', 'one_podcast_category');
    register_setting('onepodcast_settings_group', 'one_podcast_subcategory');
    register_setting('onepodcast_settings_group', 'one_podcast_email');
    register_setting('onepodcast_settings_group', 'one_podcast_copyright');

    add_settings_section('onepodcast_main_section', null, null, 'onepodcast-feed-settings');

    add_settings_field('one_podcast_title', 'Podcast Title', function () {
        echo '<input type="text" name="one_podcast_title" value="' . esc_attr(get_option('one_podcast_title')) . '" class="regular-text">';
    }, 'onepodcast-feed-settings', 'onepodcast_main_section');

    add_settings_field('one_podcast_description', 'Podcast Description', function () {
        echo '<textarea name="one_podcast_description" class="large-text" rows="3">' . esc_textarea(get_option('one_podcast_description')) . '</textarea>';
    }, 'onepodcast-feed-settings', 'onepodcast_main_section');

    add_settings_field('one_podcast_author', 'Podcast Author', function () {
        echo '<input type="text" name="one_podcast_author" value="' . esc_attr(get_option('one_podcast_author')) . '" class="regular-text">';
    }, 'onepodcast-feed-settings', 'onepodcast_main_section');

    add_settings_field('one_podcast_email', 'Owner Email', function () {
        echo '<input type="email" name="one_podcast_email" value="' . esc_attr(get_option('one_podcast_email')) . '" class="regular-text">';
    }, 'onepodcast-feed-settings', 'onepodcast_main_section');

    add_settings_field('one_podcast_copyright', 'Copyright', function () {
        echo '<input type="text" name="one_podcast_copyright" value="' . esc_attr(get_option('one_podcast_copyright')) . '" class="regular-text">';
    }, 'onepodcast-feed-settings', 'onepodcast_main_section');

    add_settings_field('one_podcast_cover_url', 'Cover Image URL', function () {
        echo '<input type="text" name="one_podcast_cover_url" value="' . esc_url(get_option('one_podcast_cover_url')) . '" class="regular-text">';
    }, 'onepodcast-feed-settings', 'onepodcast_main_section');

    add_settings_field('one_podcast_explicit', 'Explicit Content?', function () {
        $value = get_option('one_podcast_explicit', 'No');
        echo '<select name="one_podcast_explicit">
                <option value="No" ' . selected($value, 'No', false) . '>No</option>
                <option value="Yes" ' . selected($value, 'Yes', false) . '>Yes</option>
                <option value="Clean" ' . selected($value, 'Clean', false) . '>Clean</option>
              </select>';
    }, 'onepodcast-feed-settings', 'onepodcast_main_section');

    add_settings_field('one_podcast_category', 'iTunes Category', function () {
        echo '<input type="text" name="one_podcast_category" value="' . esc_attr(get_option('one_podcast_category')) . '" class="regular-text" placeholder="e.g., Leisure">';
    }, 'onepodcast-feed-settings', 'onepodcast_main_section');

    add_settings_field('one_podcast_subcategory', 'iTunes Subcategory', function () {
        echo '<input type="text" name="one_podcast_subcategory" value="' . esc_attr(get_option('one_podcast_subcategory')) . '" class="regular-text" placeholder="e.g., Video Games">';
    }, 'onepodcast-feed-settings', 'onepodcast_main_section');
}
add_action('admin_init', 'onepodcast_register_settings');

/**
 * Render the RSS feed.
 */
function onepodcast_render_feed() {
    header('Content-Type: application/rss+xml; charset=' . get_option('blog_charset'), true);
    echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';

    $title = get_option('one_podcast_title', get_bloginfo('name'));
    $description = get_option('one_podcast_description', get_bloginfo('description'));
    $author = get_option('one_podcast_author', get_bloginfo('name'));
    $email = get_option('one_podcast_email');
    $cover = get_option('one_podcast_cover_url');
    $explicit = get_option('one_podcast_explicit', 'No');
    $category = get_option('one_podcast_category', 'Leisure');
    $subcategory = get_option('one_podcast_subcategory', 'Video Games');
    $copyright = get_option('one_podcast_copyright', get_bloginfo('name'));
    $site_url = get_bloginfo('url');
    $feed_url = $site_url . '/feed/podcast';
    $language = get_bloginfo('language');

    ?>
        <rss xmlns:dc="http://purl.org/dc/elements/1.1/" 
            xmlns:content="http://purl.org/rss/1.0/modules/content/" 
            xmlns:atom="http://www.w3.org/2005/Atom" 
            version="2.0" 
            xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" 
            xmlns:anchor="https://anchor.fm/xmlns">
            
            <channel>
                <title><![CDATA[<?php echo esc_html($title); ?>]]></title>
                <description><![CDATA[<?php echo esc_html($description); ?>]]></description>
                <link><?php echo esc_url($site_url); ?></link>
                <image>
                    <url><?php echo esc_url($cover); ?></url>
                    <title><?php echo esc_html($title); ?></title>
                    <link><?php echo esc_url($site_url); ?></link>
                </image>
                <generator>One Podcast Feed Manager</generator>
                <lastBuildDate><?php echo date(DATE_RSS); ?></lastBuildDate>
                <atom:link href="<?php echo esc_url($feed_url); ?>" rel="self" type="application/rss+xml"/>
                <author><![CDATA[<?php echo esc_html($author); ?>]]></author>
                <copyright><![CDATA[<?php echo esc_html($copyright); ?>]]></copyright>
                <language><![CDATA[<?php echo esc_html($language); ?>]]></language>
                <atom:link rel="hub" href="https://pubsubhubbub.appspot.com/"/>
                <itunes:author><?php echo esc_html($author); ?></itunes:author>
                <itunes:summary><![CDATA[<?php echo esc_html($description); ?>]]></itunes:summary>
                <itunes:type>episodic</itunes:type>
                <itunes:owner>
                    <itunes:name><?php echo esc_html($author); ?></itunes:name>
                    <?php if ($email): ?>
                        <itunes:email><?php echo esc_html($email); ?></itunes:email>
                    <?php endif; ?>
                </itunes:owner>
                <itunes:explicit><?php echo esc_html($explicit); ?></itunes:explicit>
                <itunes:category text="<?php echo esc_attr($category); ?>">
                    <itunes:category text="<?php echo esc_attr($subcategory); ?>"/>
                </itunes:category>
                <?php if ($cover): ?>
                    <itunes:image href="<?php echo esc_url($cover); ?>"/>
                <?php endif; ?>

                <?php
                $args = [
                    'category_name' => 'podcast',
                    'post_type'     => 'post',
                    'orderby'       => 'date',
                    'order'         => 'DESC',
                    'posts_per_page' => -1
                ];
                $query = new WP_Query($args);

                while ($query->have_posts()) : $query->the_post();
                    $post_id = get_the_ID();
                    $audio_file_url = get_post_meta($post_id, 'episode_mp3_url', true);
                    if (!$audio_file_url) {
                        $audio_file_url = get_post_meta($post_id, 'anchor_mp3_url', true);
                    }

                    if (!$audio_file_url) {
                        continue;
                    }

                    $episode_cover = get_post_meta($post_id, 'episode_cover', true);
                    if (empty($episode_cover))
                        $episode_cover = get_the_post_thumbnail_url();

                    $duration = get_post_meta($post_id, 'episode_duration', true);
                    $episode_number = get_post_meta($post_id, 'episode_number', true);
                    $episode_season = get_post_meta($post_id, 'episode_season', true);
                    $episode_type = get_post_meta($post_id, 'episode_type', true);
                    $explicit = get_post_meta($post_id, 'episode_explicit', true) ?: 'No';

                    $pub_date = get_the_date(DATE_RSS);
                    $guid = get_post_meta($post_id, 'episode_guid', true) ?: get_permalink();
                    $title = get_the_title();
                    $content = get_the_content_feed('rss2');
                    $filesize = 0;

                    $headers = wp_remote_head($audio_file_url);
                    if (!is_wp_error($headers)) {
                        $filesize = isset($headers['headers']['content-length']) ? $headers['headers']['content-length'] : 0;
                    }
                ?>
                    <item>
                        <title><![CDATA[<?php echo esc_html($title); ?>]]></title>
                        <description><![CDATA[<?php echo preg_replace('/\s+/', ' ', wp_strip_all_tags($content)); ?>]]></description>
                        <link><?php echo esc_url(get_permalink()); ?></link>
                        <guid isPermaLink="false"><?php echo esc_html($guid); ?></guid>
                        <dc:creator><![CDATA[<?php echo esc_html($author); ?>]]></dc:creator>
                        <pubDate><?php echo esc_html($pub_date); ?></pubDate>
                        <enclosure url="<?php echo esc_url($audio_file_url); ?>" length="<?php echo esc_attr($filesize); ?>" type="audio/mpeg"/>
                        <itunes:summary><![CDATA[<?php echo preg_replace('/\s+/', ' ', wp_strip_all_tags($content)); ?>]]></itunes:summary>
                        <itunes:explicit><?php echo esc_html($explicit); ?></itunes:explicit>
                        <?php if ($duration): ?>
                            <itunes:duration><?php echo esc_html($duration); ?></itunes:duration>
                        <?php endif; ?>
                        <?php if ($episode_cover): ?>
                            <itunes:image href="<?php echo esc_url($episode_cover); ?>"/>
                        <?php endif; ?>
                        <?php if ($episode_season): ?>
                            <itunes:season><?php echo esc_html($episode_season); ?></itunes:season>
                        <?php endif; ?>
                        <?php if ($episode_number): ?>
                            <itunes:episode><?php echo esc_html($episode_number); ?></itunes:episode>
                        <?php endif; ?>
                        <?php if ($episode_type): ?>
                            <itunes:episodeType><?php echo esc_html($episode_type); ?></itunes:episodeType>
                        <?php endif; ?>
                    </item>
                <?php endwhile; wp_reset_postdata(); ?>
            </channel>
        </rss>
    <?php
}
