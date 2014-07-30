<?php
/**
 * Plugin Name: Twitter Embed
 * Description: A simple widget that embeds a Twitter timeline. See https://dev.twitter.com/docs/embedded-timelines
 * Version: 0.1
 * Author: Ben Wallis
 * Author URI: http://www.benedict-wallis.com
 */

add_action('widgets_init', 'WP_Twitter_Embed');

/**
 * twitter_embed_widget
 */
function WP_Twitter_Embed() {
    register_widget('WP_Twitter_Embed');
}

class WP_Twitter_Embed extends WP_Widget {

    /**
     * WP_Twitter_Embed
     *
     * @return void
     */
    function WP_Twitter_Embed() {

        $widget_ops = array(
            'classname' => 'twitter-embed-widget',
            'description' => __('A simple widget that embeds a Twitter timeline. See https://dev.twitter.com/docs/embedded-timelines', 'twitter_embed_widget')
        );

        $control_ops = array(
            'id_base' => 'twitter-embed-widget'
        );

        $this->WP_Widget('twitter-embed-widget', __('Twitter Embed Widget', 'twitter_embed_widget'), $widget_ops, $control_ops);
    }

    /**
     * widget
     *
     * @param array $args
     * @param array $instance
     */
    function widget( $args, $instance ) {

        extract( $args );

        // widget heading
        $title = apply_filters('widget_title', $instance['title'] );

        // variables from the widget settings
        $handle = $instance['handle'];

        // "do not track" - https://support.twitter.com/articles/20169453-twitter-supports-do-not-track
        $dnt = true;

        // widget id - from https://dev.twitter.com/docs/embedded-timelines
        $widget_id = $instance['widget_id'];

        // no. of tweets
        $limit = $instance['limit'];

        ?>

        <?php echo $before_widget; ?>
        <div id="twitter-feed">
            <?php if ($title) : ?>
            <h3><?php echo $before_title . $title . $after_title; ?></h3>
            <?php endif; ?>
            <a class="twitter-timeline" href="https://twitter.com/<?php echo $handle; ?>"
               data-dnt="<?php echo $dnt; ?>"
               data-widget-id="<?php echo $widget_id; ?>"
               data-chrome="noheader nofooter noborders noscrollbar transparent"
               data-tweet-limit="<?php echo $limit; ?>"><?php echo __("Tweets by @{$handle}", ''); ?></a>
            <script type="text/javascript">
                // <![CDATA[
                !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
                // ]]>
            </script>
        </div>
        <?php echo $after_widget;
    }

    /**
     * Update Widget
     *
     * Saves the widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        // strip tags from title and name to remove HTML
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['handle'] = strip_tags($new_instance['handle']);
        $instance['widget_id'] = strip_tags($new_instance['widget_id']);
        $instance['limit'] = strip_tags($new_instance['limit']);

        return $instance;
    }

    /**
     * Form
     *
     * @param array $instance
     * @return string|void
     */
    function form( $instance ) {

        // default widget settings
        $defaults = array(
            'title' => __('Twitter Feed', 'twitter_embed_widget'),
            'handle' => '',
            'dnt' => true,
            'widget_id' => '',
            'limit' => 5,
        );

        $instance = wp_parse_args((array)$instance, $defaults); ?>

        <!-- Widget Title -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'twitter_embed_widget'); ?></label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>

        <!-- Twitter Handle -->
        <p>
            <label for="<?php echo $this->get_field_id('handle'); ?>"><?php _e('Twitter Handle:', 'twitter_embed_widget'); ?></label>
            <input id="<?php echo $this->get_field_id('handle'); ?>" name="<?php echo $this->get_field_name('handle'); ?>" value="<?php echo $instance['handle']; ?>" style="width:100%;" />
        </p>

        <!-- Widget ID -->
        <p>
            <label for="<?php echo $this->get_field_id('widget_id'); ?>"><?php _e('Widget ID:', 'twitter_embed_widget'); ?></label>
            <input id="<?php echo $this->get_field_id('widget_id'); ?>" name="<?php echo $this->get_field_name('widget_id'); ?>" value="<?php echo $instance['widget_id']; ?>" style="width:100%;" />
        </p>

        <!-- Limit -->
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:', 'twitter_embed_widget'); ?></label>
            <input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo $instance['limit']; ?>" style="width:100%;" />
        </p>

    <?php
    }
}

?>