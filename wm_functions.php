<?php

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
    $output = '<div id="icons" class="social-links url center-text" >'; // TODO need to get rid of center tag
    // Find ending key
    end($accounts);
    $last_key = key($accounts);

    foreach ($accounts as $account => $url) {
        $output .= '  <a class="fa fa-'. $account . '" href="'
        . $url.'" title="'. $account .'" target="_blank"></a>';
        $output .= ($last_key != $account) ? '&nbsp&middot&nbsp' : '';
    }

    return $output . '</div>';

}