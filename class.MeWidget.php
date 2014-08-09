<?php
/*
Copyright (C) 2014  Kyly G. Vass

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details (http://www.gnu.org/licenses/).

*/

/**
 * Add MeWidget
 */
class MeWidget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {

        // Register style sheet.
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));

        parent::__construct(
            'widget_me', // Base ID
            __('Me Widget', 'text_domain'), // Name
            array('description' => __('Widget showing my info.', 'text_domain'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        // Locals
        $title        = apply_filters('widget_title', $instance['title']);
        $grav_email   = $instance['gravatar_account'];
        $email_format = '<div class="email"><a href="mailto:%s">%s</a></div>';
        $adr_format   = '<div class="adr">%s</div>';
        $tel_format   = '<div class="tel">%s</div>';
        $about_format = '<div class="about">%s</div>';
        $soc_format   = '<div id="icons" class="url social-links">%s</div>';
        $vcard_head   = '<div id="%s" class="vcard">';
        $name_format  = '<div class="fn n">%s</div>';
        $html         = '';
        $image        = '';


        // Parse attributes
        $image_attr = str_replace(',', '&', $instance['image_attr']);
        parse_str($image_attr, $img_attr_array);

        echo $args['before_widget'];

        if (!empty($title)) {
            $html .= $args['before_title'] . $title . $args['after_title'];
        }

        // widget wrapper
        $html .= '<div id="me-widget" class="me-widget">';

        if ( empty( $instance['custom_img_url'] ) ){
            $html .= $this->get_gravatar(array(
                'hash' => $instance['hash'],
                'size' => (int) $instance['image_size'],
                'attr' => $img_attr_array)
            );
        } else {
            $html .= $this->get_custom_avatar($instance['custom_img_url'], $img_attr_array);
        }

        $html .= sprintf($vcard_head, $instance['name']);

        if (!empty($instance['option_name']) && !empty($instance['name']))
            $html .= sprintf($name_format, $instance['name']);
        if (!empty($instance['option_email']))
            $html .= sprintf($email_format, $grav_email, $grav_email);
        if (!empty($instance['phone']) && !empty($instance['option_phone']))
            $html .= sprintf($tel_format, $instance['phone']);
        if (!empty($instance['current_location']) && !empty($instance['option_curr_loc']))
            $html .= sprintf($adr_format, $instance['current_location']);
        if (!empty($instance['about_me']) && !empty($instance['option_about_me']))
            $html .= sprintf($about_format, $instance['about_me']);
        if (!empty($instance['accounts']) && !empty($instance['option_accounts']))
            $html .= sprintf($soc_format, $this->get_accounts_icons($instance['accounts']));

        // close vcard
        $html .= '</div>';
        $html .= '</div>';

        echo wpautop($html);

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     * @return none
     */
    public function form($instance)
    {

        // Setup defaults
        $defaults = array(
            'image_attr'       => 'width=100%',
            'update'           => true,
            'image_size'       => 200,
            'title'            => '',
            'gravatar_account' => '',
            'hash'             => '',
            'option_name'      => '',
            'option_about_me'  => '',
            'option_phone'     => '',
            'option_email'     => '',
            'option_curr_loc'  => '',
            'option_accounts'  => '',
            'name'             => '',
            'email'            => '',
            'about_me'         => '',
            'phone'            => '',
            'current_location' => '',
            'accounts'         => '',
            'custom_img_url'   => ''
        );

        // Parse existing values with defaults
        $instance = wp_parse_args((array)$instance, $defaults);

        //options - this is the order the option appear in dashboard
        $options = array(
            'option_email'    => 'email',
            'option_name'     => 'name',
            'option_phone'    => 'phone',
            'option_curr_loc' => 'current_location',
            'option_accounts' => 'accounts',
            'option_about_me' => 'about_me'
        );

        // Locals
        $enabled    = false;
        $accounts   = array();
        $sample_img = '';
        $name       = '';
        $error      = '';
        $message    = '';
        $acc_icons  = '';

        // If gravatar_account is set but profile data has not been set
        // show error.
        if (!empty($instance['gravatar_account'])) {

            // Check if Gravatar data was read
            if (!empty($instance['hash'])) {

                $enabled  = true;
                $accounts = unserialize($instance['accounts']);

                // Format accounts output for preview
                if (!empty($instance['accounts'])) {
                    foreach ($accounts as $account => $url) {
                        $acc_icons .= '  <span class="fa fa-' . $account
                            . '"> </span>  ';
                    }
                }
            } // Show error if unable to read account data
            else {
                $error = "Unable to read your Gravatar meta data. Check if the"
                    . " email you entered is the same email used by your"
                    . " Gravatar account.";
            }

        } // No email is entered
        else {

            $instance = $defaults;
            $message = "Press save to check Gravatar account email "
                . " <em>then</em> select from tho fallowing options.";
        }

        // Give sample of image
        if (empty($instance['custom_img_url'])) {
            $sample_img = $this->get_gravatar(
                array(
                    'hash' => $instance['hash'],
                    'size' => 80,
                    'attr' => array('class' => 'thumbnail', 'width' => '')
                )
            );
        } else {
            $sample_img = $this->get_custom_avatar($instance['custom_img_url'],
                array(
                    'class' => 'thumbnail',
                    'width' => '100px',
                )
            );
        }
        ?>
        <strong style="color: red;" class="alert">
            <?= $error ?>
        </strong>
        <input type="hidden" id="<?php
        echo $this->get_field_id('hash'); ?>" name="<?php
        echo $this->get_field_name('hash'); ?>" type="text" value="<?php
        echo esc_attr($instance['hash']); ?>"> <!-- hash input -->
        <p>
            <label for="<?php
            echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php
            echo $this->get_field_id('title'); ?>" name="<?php
            echo $this->get_field_name('title'); ?>" type="text" value="<?php
            echo esc_attr($instance['title']); ?>">
        </p>
        <p>
            <label for="<?php
            echo $this->get_field_id('gravatar_account'); ?>"><?php _e('Gravatar Account:'); ?></label>
            <input class="widefat" id="<?php
            echo $this->get_field_id('gravatar_account'); ?>" name="<?php
            echo $this->get_field_name('gravatar_account'); ?>" type="text" value="<?php
            echo esc_attr($instance['gravatar_account']); ?>">
        </p>
        <p>
        <p><?= $message ?></p>
        <p><strong>Options</strong></p>
        <!-- check boxes for options -->
        <?php foreach ($options as $opt => $val): ?>
        <?php $instance_val = ($enabled) ? $instance[$val] : ''; ?>
        <?php $instance_opt = ($enabled) ? $instance[$opt] : ''; ?>
        <input class="checkbox" type="checkbox" <?php
        checked($instance_opt, 'on'); ?> id="<?php
        echo $this->get_field_id($opt); ?>" name="<?php
        echo $this->get_field_name($opt); ?>" <?php
        echo $enabled ? 'enabled' : 'disabled'; ?>/>
        <label for="<?php
        echo $this->get_field_id($opt); ?>"><?php
            echo str_replace('_', ' ', $val);
            echo ($val != 'accounts') ? ' [<em>' . $instance_val . '</em>]' : ' [' . $acc_icons . ']'; ?>
        </label>
        <?php if ($val != 'gravatar_account'): ?>
            <input type="hidden" id="<?php
            echo $this->get_field_id($val); ?>" name="<?php
            echo $this->get_field_name($val); ?>" type="text" value="<?php
            echo esc_attr($instance_val); ?>"> <!-- <?= $val ?> input -->
            </br>
        <?php endif; ?>
    <?php endforeach; ?>
        <!-- end check boxes for options -->
        </p>
        <p>
        <?php if (!empty($instance['hash']) && $enabled): ?>
        <?= $sample_img ?>
        <p>
            <label for="<?php
            echo $this->get_field_id('custom_img_url'); ?>"><?php _e('Custom Image Url:'); ?></label>
            <input class="widefat" id="<?php
            echo $this->get_field_id('custom_img_url'); ?>" name="<?php
            echo $this->get_field_name('custom_img_url'); ?>" type="text" value="<?php
            echo esc_attr($instance['custom_img_url']); ?>">
        </p>
        <p>
            <label for="<?php
            echo $this->get_field_id('image_attr'); ?>"><?php _e('Image attributes:'); ?></label>
            <input class="widefat" id="<?php
            echo $this->get_field_id('image_attr'); ?>" name="<?php
            echo $this->get_field_name('image_attr'); ?>" type="text" value="<?php
            echo esc_attr($instance['image_attr']); ?>">
        </p>
        <em> Attributes must follow the following format. ex. <code>name=value</code>,
            for more then one use commas. ex <code>n1=v1, n2=v2, n3=v3</code></em>
        <?php if (empty($instance['custom_img_url'])) : ?>
            <p>
                <label for="<?php
                echo $this->get_field_id('image_size'); ?>"><?php _e('Image size:'); ?></label>
                <input class="widefat" id="<?php
                echo $this->get_field_id('image_size'); ?>" name="<?php
                echo $this->get_field_name('image_size'); ?>" type="text" value="<?php
                echo esc_attr($instance['image_size']); ?>">
            </p>
        <?php endif; ?>
    <?php endif; ?>
    <?php

    }

     /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $values = array(
            'gravatar_account', 'image_attr', 'option_name',
            'option_about_me', 'option_phone', 'option_email',
            'option_curr_loc', 'option_accounts', 'title',
            'option_accounts', 'custom_img_url'
        );

        $instance = array();

        $instance = $this->get_profile_data($new_instance);

        // Check if valid int is passed
        $image_size = $new_instance['image_size'];
        $instance['image_size'] = (!empty($image_size) || is_numeric($image_size))
            ? $image_size : 200;

        foreach ($values as $value) {
            $instance[$value] = (!empty($new_instance[$value]))
                ? strip_tags($new_instance[$value])
                : '';
        }

        return $instance;
    }

    /**
     * Register and enqueue style sheet.
     */
    public function register_plugin_styles()
    {
        wp_register_style('MeWidget', ME_WIDGET__PLUGIN_URL. '/css/plugin.css' );
        wp_enqueue_style('MeWidget');

        wp_register_style('font-awesome',
            ME_WIDGET__PLUGIN_URL. '/css/font-awesome.min.css', null, '4.1.0');
        wp_enqueue_style('font-awesome');

    }

    /*
     * -----------------------------------------------------------------------
     * The following are private helper methods that make the core
     * functionality of Me Widget.
     * -----------------------------------------------------------------------
     */

    /**
     * Gets a completed image tag for custom avatar image
     *
     * @param string $url path to image
     * @param array $attr Attribute for image tag
     * @return string complete image tag for custom avatar
     */
    private function get_custom_avatar( $url, $attr ) {
        $default_attr = array( 'width' => '100%', 'class' => 'none' );
        $attr = wp_parse_args($attr, $default_attr);
        $url = '<img src="'.$url.'"';

        foreach ($attr as $key => $val)
            $url .= ' '.$key.'="'.$val.'"';
        $url .= ' />';

        return $url;
    }

    /**
     * Check if icon exists in font using its name.
     * @param string $name is checked list of site icon names from Awesome Font.
     * @return bool True if $name is matched.
     */
    private function icon_exist( $name ) {
        $soc_array = array(
            'bitbucket','apple','bitcoin','btc','code-fork','codepen','delicious',
            'deviantart','digg','dribbble','dropbox','drupal','empire','facebook',
            'flickr','foursquare','gamepad','ge','git','github','gittip','google',
            'google-plus','hacker-news','instagram','joomla','jpy','jsfiddle','linkedin',
            'linux','pied-piper','pinterest','qq','reddit','rocket','rss','skype','slack',
            'soundcloud','space-shuttle','spotify','stack-exchange','stack-overflow',
            'steam','stumbleupon','tumblr','twitter','vine','vk','wechat','weibo','weixin'
            ,'windows','wordpress','xing','yahoo','youtube'
        );

        return in_array( $name, $soc_array );
    }

    /**
     * Outputs social link icons.
     * @param string $accounts serialized array of Gravatar verified accounts info.
     * @return string
     */
    private function get_accounts_icons( $accounts ) {

        // Check if any accounts where given
        if ( empty( $accounts ) )
            return;

        // Get accounts array.
        $accounts = unserialize( $accounts );
        $output = '';
        // Find ending key
        end( $accounts );
        $last_key = key( $accounts );

        foreach ( $accounts as $account => $url ) {
            if ( ! $this->icon_exist( $account ) )
                continue;

            $output .= '  <a class="fa fa-'. $account . '" href="'
            . $url.'" title="'. $account .'" target="_blank"></a>';
            $output .= ( $last_key != $account ) ? '&nbsp&middot&nbsp' : '';
        }

        return $output;
    }

    /**
     * Complete image tag for a specified email address.
     *
     * @param array $args parameters array containing the following:
     *      - $email The email address
     *      - string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     *      - string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     *      - string $r Maximum rating (inclusive) [ g | pg | r | x ]
     *      - array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    private function get_gravatar($args) {

        $default_attr = array( 'width' => '100%', 'class' => 'circle' );
        $defaults = array(
            'hash' => '',
            'size' => 200,
            'd'    => 'mm',
            'r'    => 'x',
            'img'  => true,
            'attr' => array()
        );

        $args = wp_parse_args( $args, $defaults );

        extract( $args, EXTR_SKIP );
        $attr = wp_parse_args($attr, $default_attr);

        if ( empty( $size ) ) $size = 200;

        $url = 'http://www.gravatar.com/avatar/';
        $url .= $hash;
        $url .= "?s=$size&d=$d&r=$r";

        $url = '<img src="'.$url.'"';
        foreach ($attr as $key => $val)
            $url .= ' '.$key.'="'.$val.'"';

        $url .= ' />';

        return $url;
    }

    /**
     * Gets key/value hash of Gravatar profile data from http://www.gravatar.com/
     *
     * @param string $email is the account email of the Gravatar user.
     * @return profile data for Gravatar user
     */
    private function get_grav_profile( $email ) {

        $email      = trim( $email );// "MyEmailAddress@example.com"
        $email      = strtolower( $email );// "myemailaddress@example.com"
        $email_hash = md5( $email );// "0bc83cb571cd1c50ba6f3e8a78ef1346"

        // Get data from Gravitar
        $str     = wp_remote_fopen( 'http://www.gravatar.com/'.$email_hash.'.php' );
        $profile = unserialize( $str );

        return $profile;
    }

    /**
     * Update profile information
     *
     * @param array $new_instance contains all fields associated with Me Widget
     * @return array Data from Gravatar profile
     */
    private function get_profile_data($new_instance)
    {
        // Guarantee that all the following are initialized
        $defaults = array(
            'hash'             => '',
            'email'            => '',
            'name'             => '',
            'about_me'         => '',
            'phone'            => '',
            'current_location' => '',
            'accounts'         => ''
        );

        wp_parse_args( (array) $new_instance, $defaults );

        if (!empty($new_instance['gravatar_account'])) {

            $profile = $this->get_grav_profile($new_instance['gravatar_account']);

            // load options
            if (isset($profile['entry'])) {

                $profile = $profile['entry'][0];
                $new_instance['hash'] = $profile['hash'];


                // Set option values
                if (isset($profile['emails'])) {
                    foreach ($profile['emails'] as $email => $data) {
                        if ($data['primary']) {
                            $new_instance['email'] = strip_tags($data['value']);
                        }
                    }
                }

                $new_instance['name'] = (isset($profile['displayName']))
                    ? strip_tags($profile['displayName']) : '';
                $new_instance['about_me'] = (isset($profile['aboutMe']))
                    ? strip_tags($profile['aboutMe']) : '';
                $new_instance['phone'] = (isset($profile['phoneNumbers'][0]['value']))
                    ? strip_tags($profile['phoneNumbers'][0]['value'])
                    : '';
                $new_instance['current_location'] = (isset($profile['currentLocation']))
                    ? strip_tags($profile['currentLocation'])
                    : '';
                if (isset($profile['accounts'])) {
                    for ($i = 0; $i < count($profile['accounts']); $i++) {
                        $name = $profile['accounts'][$i]['shortname'];
                        $url = $profile['accounts'][$i]['url'];
                        if ($name == 'google') $name .= '-plus';
                        $accounts[$name] = $url;
                    }

                    $new_instance['accounts'] = serialize($accounts);
                }
            }
        }

        return $new_instance;
    }


} // class MeWidget

