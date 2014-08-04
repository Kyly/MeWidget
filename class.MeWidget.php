<?php

/**
 * Add MeWidget
 */
class MeWidget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {

        // Register style sheet.
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );

        parent::__construct(
            'me_widget', // Base ID
            __('Me Widget', 'text_domain'), // Name
            array( 'description' => __( 'Widget showing my info.', 'text_domain' ), ) // Args
        );
    }

    /**
     * Register and enqueue style sheet.
     */
    public function register_plugin_styles() {
        wp_register_style( 'MeWidget', plugins_url( 'me-widget/css/plugin.css' ) );
        wp_enqueue_style( 'MeWidget' );

        wp_register_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css',
            null, '4.1.0' );
        wp_enqueue_style('font-awesome');

    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {

        // Locals
        $title        = apply_filters( 'widget_title', $instance['title'] );
        $grav_email   = $instance['gravatar_account'];
        $email_format = '<a class="email" href="mailto:%s"> %s </a>';
        $adr_format   = '<div class="adr">%s</div>';
        $tel_format   = '<div class="tel">%s</div>';
        $about_format = '<div class="about">%s</div>';
        $soc_format   = '<div id="icons" class="url social-links">%s</div>';
        $vcard_head   = '<div id="%s" class="vcard">';
        $name_format  = '<div class="fn n">%s</div>';
        // Parse attributes
        $image_attr = str_replace(',', '&', $instance['image_attr']);
        parse_str($image_attr, $img_attr_array);

        echo $args['before_widget'];
        // widget wrapper
        echo '<div id="me-widget" class="me-widget">';

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo get_gravatar(array(
            'hash' => $instance['hash'],
            'size' => (int) $instance['image_size'],
            'attr' => $img_attr_array)
        );

        printf($vcard_head, $instance['name']);

        if (!empty($instance['option_name']) && !empty($instance['name']))
            printf($name_format, $instance['name']);
        if (!empty($instance['option_email']))
            printf($email_format, $grav_email, $grav_email);
        if (!empty($instance['phone']) && !empty($instance['option_phone']))
            printf($tel_format, $instance['phone']);
        if (!empty($instance['current_location']) && !empty($instance['option_curr_loc']))
            printf($adr_format, $instance['current_location']);
        if (!empty($instance['about_me']) && !empty($instance['option_about_me']))
            printf($about_format, $instance['about_me']);
        if (!empty($instance['accounts']) && !empty($instance['option_accounts']))
            printf($soc_format, get_accounts_icons($instance['accounts']));

        // close vcard
        echo '</div> <!--vcard-->';
        echo '</div> <!--me-widget-->';
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        // Setup defaults
        $defaults = array(
            'title'            => '',
            'gravatar_account' => '',
            'image_attr'       => 'width=100%',
            'image_size'       => 200,
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
            'accounts'         => ''
        );

        // Parse existing values with defaults
        $instance = wp_parse_args( (array) $instance, $defaults );

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
        $name      = '';
        $error     = '';
        $message   = '';
        $acc_icons = '';
        $enabled   = false;
        $accounts  = array();


        // If gravatar_account is set but profile data has not been set
        // show error.
        if (!empty($instance['gravatar_account'])) {

            $profile = get_grav_profile($instance['gravatar_account']);

            // load options
            if (isset($profile['entry'])) {
                $enabled          = true;
                $profile          = $profile['entry'][0];
                $instance['hash'] = $profile['hash'];


                // Set option values
                if (isset($profile['emails']))
                    foreach ($profile['emails'] as $email => $data) {
                        if ($data['primary']) {
                            $instance['email'] = strip_tags($data['value']);
                        }
                    }

                $instance['name']     = (isset($profile['displayName']))
                                            ? strip_tags($profile['displayName']): '';
                $instance['about_me'] = (isset($profile['aboutMe']))
                                            ? strip_tags($profile['aboutMe']) : '';
                $instance['phone']    = (isset($profile['phoneNumbers'][0]['value']))
                                            ? strip_tags($profile['phoneNumbers'][0]['value'])
                                                : '';
                $instance['current_location'] = (isset($profile['currentLocation']))
                                                    ? strip_tags($profile['currentLocation'])
                                                        : '';
                if (isset($profile['accounts'])) {
                    for ($i = 0; $i < count($profile['accounts']); $i++) {
                        $name = $profile['accounts'][$i]['shortname'];
                        $url  = $profile['accounts'][$i]['url'];
                        if($name == 'google') $name .= '-plus';
                        $accounts[$name] = $url;
                        $instance['accounts'] = serialize($accounts);
                    }
                    foreach ($accounts as $account => $url) {
                        $acc_icons .= '  <span class="fa fa-'. $account
                                        . '"> </span>  ';
                    }
                }
            } else {
                $error = "Unable to read your Gravatar meta data. Check if the"
                    . " email you entered is the same email used by your"
                    . " Gravatar account.";
            }

        } else {

            $instance = $defaults;
            $message  = "Press save to check Gravatar account email "
                    . " <em>then</em> select from tho fallowing options.";
        }

    ?>
    <strong style="color: red;" class="alert">
        <?=$error?>
    </strong>
    <input type="hidden" id="<?php
        echo $this->get_field_id( 'hash' ); ?>" name="<?php
        echo $this->get_field_name( 'hash' ); ?>" type="text" value="<?php
        echo esc_attr( $instance['hash'] ); ?>"> <!-- hash input -->
    <p>
    <label for="<?php
        echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php
        echo $this->get_field_id( 'title' ); ?>" name="<?php
        echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php
        echo esc_attr( $instance['title'] ); ?>">
    </p>
    <p>
    <label for="<?php
        echo $this->get_field_id( 'gravatar_account' ); ?>"><?php _e( 'Gravatar Account:' ); ?></label>
    <input class="widefat" id="<?php
        echo $this->get_field_id( 'gravatar_account' ); ?>" name="<?php
        echo $this->get_field_name( 'gravatar_account' ); ?>" type="text" value="<?php
        echo esc_attr( $instance['gravatar_account'] ); ?>">
    </p>
    <p>
    <p><?=$message?></p>
    <p><strong>Options</strong></p>
    <!-- check boxes for options -->
    <?php foreach ($options as $opt => $val):?>
        <?php $instance_val = ( $enabled ) ? $instance[$val] : ''; ?>
        <?php $instance_opt = ( $enabled ) ? $instance[$opt] : ''; ?>
        <input class="checkbox" type="checkbox" <?php
            checked($instance_opt, 'on'); ?> id="<?php
            echo $this->get_field_id($opt); ?>" name="<?php
            echo $this->get_field_name($opt); ?>" <?php
            echo $enabled ? 'enabled' : 'disabled'; ?>/>
        <label for="<?php
            echo $this->get_field_id($opt); ?>"><?php
            echo str_replace('_', ' ', $val);
            echo ($val != 'accounts') ? ' [<em>'.$instance_val.'</em>]': ' ['. $acc_icons .']'; ?>
        </label>
        <?php if ($val != 'gravatar_account'): ?>
            <input type="hidden" id="<?php
                echo $this->get_field_id( $val ); ?>" name="<?php
                echo $this->get_field_name( $val ); ?>" type="text" value="<?php
                echo esc_attr( $instance_val ); ?>"> <!-- <?=$val?> input -->
            </br>
        <?php endif; ?>
    <?php endforeach; ?>
    <!-- end check boxes for options -->
    </p>
    <p>
    <?php if (!empty($instance['hash']) && $enabled): ?>
        <?=get_gravatar(array(
            'hash' => $instance['hash'],
            'size' => 80,
            'attr' => array('class' => 'thumbnail', 'width' => ''))
        )?>
        <p>
        <label for="<?php
            echo $this->get_field_id( 'image_attr' ); ?>"><?php _e( 'Image attributes:' ); ?></label>
        <input class="widefat" id="<?php
            echo $this->get_field_id( 'image_attr' ); ?>" name="<?php
            echo $this->get_field_name( 'image_attr' ); ?>" type="text" value="<?php
            echo esc_attr( $instance['image_attr'] ); ?>">
        </p>
        <em> Attributes must follow the following format. ex. <code>name=value</code>,
        for more then one use commas. ex <code>n1=v1, n2=v2, n3=v3</code>
        <p>
        <label for="<?php
            echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image size:' ); ?></label>
        <input class="widefat" id="<?php
            echo $this->get_field_id( 'image_size' ); ?>" name="<?php
            echo $this->get_field_name( 'image_size' ); ?>" type="text" value="<?php
            echo esc_attr( $instance['image_size'] ); ?>">
        </p>
    <?php endif; ?>
    </p>
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
    public function update( $new_instance, $old_instance ) {
        $values = array(
            'hash','title','gravatar_account','image_attr','image_size','option_name',
            'option_about_me','option_phone','option_email',
            'option_curr_loc','option_accounts','name','about_me','phone',
            'current_location', 'option_accounts', 'accounts'
        );

        $instance = array();
        // $instance['accounts'] = ( ! empty( $new_instance['accounts']))
        //                             ? $new_instance['accounts']
        //                                 : '';

        foreach ($values as $value) {
            $instance[$value] = ( ! empty( $new_instance[$value] ) )
                                    ? strip_tags( $new_instance[$value] )
                                        : '';
        }
        return $instance;
    }

} // class MeWidget

