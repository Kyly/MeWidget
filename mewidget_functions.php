<?php

function load_font_awesome_admin_style($hook) {
    if ('widgets.php' != $hook)
        return;

    wp_enqueue_style( 'font-awesome',
        '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css',
        null, '4.1.0' );

    wp_register_style( 'MeWidget', plugins_url( 'me-widget/css/plugin.css' ) );
    wp_enqueue_style( 'MeWidget' );
}


function icon_exist($name) {
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

    return in_array($name, $soc_array);
}
function get_grav_profile($email) {

    $email      = trim($email);// "MyEmailAddress@example.com"
    $email      = strtolower($email);// "myemailaddress@example.com"
    $email_hash = md5($email);// "0bc83cb571cd1c50ba6f3e8a78ef1346"

    // Get data from Gravitar
    $str     = file_get_contents('http://www.gravatar.com/'.$email_hash.'.php');
    $profile = unserialize($str);

    return $profile;
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar($args) {

    // $email_hash, $s = 200, $d = 'mm', $r = 'g', $img = true, $atts = array()
    $default_attr = array('width' => '100%', 'class' => 'circle');
    $defaults = array(
        'hash' => '',
        'size' => 200,
        'd'    => 'mm',
        'r'    => 'x',
        'img'  => true,
        'attr' => array()
    );
    $args = wp_parse_args( $args, $defaults );

    extract($args, EXTR_SKIP);
    $attr = wp_parse_args($attr, $default_attr);

    if (empty($size)) $size = 200;

    $url = 'http://www.gravatar.com/avatar/';
    $url .= $hash;
    $url .= "?s=$size&d=$d&r=$r";

    if ($img) {
        $url = '<img src="'.$url.'"';
        foreach ($attr as $key => $val)
            $url .= ' '.$key.'="'.$val.'"';
        $url .= ' />';
    }
    return $url;
}

/**
 * Outputs social link icons.
 * @param string $accounts serialized array of Gravatar verified accounts info.
 * @return string
 */
function get_accounts_icons($accounts) {

    // Check if any accounts where given
    if (empty($accounts))
        return;

    // Get accounts array.
    $accounts = unserialize($accounts);
    $output = '';
    // Find ending key
    end($accounts);
    $last_key = key($accounts);

    foreach ($accounts as $account => $url) {
        if (!icon_exist($account))
            continue;

        $output .= '  <a class="fa fa-'. $account . '" href="'
        . $url.'" title="'. $account .'" target="_blank"></a>';
        $output .= ($last_key != $account) ? '&nbsp&middot&nbsp' : '';
    }

    return $output;

}

function get_vcard_head($name) {
    $output;
    if (!empty($name)) {
        $name_array = explode(' ', $name);
        $output  = '<div id="hcard-' . $name_array[0]
                    . ((!empty($name_array[1])) ? '-'.$name_array[1]: '')
                    . ((!empty($name_array[2])) ? '-'.$name_array[2]: '')
                    . '" class="vcard">';
        $output .= '<span class="fn n">'
                    . '<span class="given-name">'
                    . $name_array[0] . '</span>';
        $output .= (!empty($name_array[1]))
                    ? '<span class="additional-name">&nbsp' . $name_array[1] . '</span>'
                        : '';
        $output .= (!empty($name_array[2]))
                    ? '<span class="family-name">&nbsp' . $name_array[2] . '</span>'
                        : '';
        $output .= '</span></br>';
    } else {
        $output  = '<div id="" class="vcard">';
    }
    return $output;
}