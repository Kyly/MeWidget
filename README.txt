=== Me Widget ===
Contributors: Kyly
Tags: gravatar, profile, contact, contact info, avatar, customization
Requires at least: 3.9.0 or greater
Tested up to: 3.9.2
Stable tag: 1.0.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a simple way to incorporate and customize your Gravatar profile in a easy to style widget.

== Description ==
_**Me Widget**_ is a widget designed to be used with Wordpress 3.9 sites, and requires
having a [Gravatar](https://gravatar.com) profile. *Me Widget* is similar to
[Jetpacks Gravatar Profile Widget](http://jetpack.me/support/extra-sidebar-widgets/gravatar-profile-widget/)
but provides extra customization like using a avatar not on your [Gravatar](https://gravatar.com)
profile or adding custom class tag to your avatar image easily from your

= Features =
- **_Avatar_**        : Display your Gravatar or a custom avatar.
- **_Profile Info_**  : Display some, all, or none of the personal information
from your [Gravatar](https://gravatar.com) profile.
- **_Customize_**     : the way your avatar and profile data is displayed using
preset styles.
- **_CSS_**           : Easy to edit or make your own styles by adding the style
template to your existing style sheet
- **_Social Icons_**\*  : Displays icons for your verified accounts from your
Wordpress profile.

\*Icons are provided by [Font Awesome 4.1.0](http://fortawesome.github.io/Font-Awesome/)
created by [Dave Gandy](https://twitter.com/davegandy), the
font\'s cheat sheet is found [here](http://fortawesome.github.io/Font-Awesome/cheatsheet/),
and the list that the widget recognizes is found [here](https://github.com/Kyly/MeWidget/blob/master/soc_icons_fa)
(_This list may not be complete, if there is an icon that is_
 _in Awesome Fonts that should be included in this list please let me know_).

== Installation ==
_Requirements_: Wordpress 3.9.\* and PHP 5.

1. Upload the entire me-widget folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the \'Plugins\' menu in WordPress.

For more information on how to set up the widget please go to the [Me Widget site](https://github.com/Kyly/MeWidget).

= Setup =

- Place the widget into one of your side bars then under the
widget option first put in a `Gravatar Account` email you wish to display then `save`.
If the email that was entered is valid you can then begin to select some of the options.
- A small preview of the data that is contained within each option will be shown
to the right of the check box label. If no data was retrieved for that item it
will be left blank.
- If you would like an image other than the one provided by Gravatar, enter
the url to the image in the `Custom Image Url` text box.
- With `Image size` you can set the pixel width of the image _(Note: this
is not the same as the display width)_. This option is not available for custom
images.
- You can add attributes by listing them in `Image attributes` text box.
Example:
`width=30%, class=thumbnail right-align`
gets converted to
`<img src="http://localhost/kylyv/wp-content/uploads/me_graphic.png" width="30%" class="thumbnail right-align">`

= Styling =
A few styles have already been provided for you but you will most likely want to
make more. An example CSS has been provided for you in the `css` folder named
me-widget-example.css.
Simply fill it in with your own styles. For images you can make your own
selectors then add them using the `Image attributes` dialog.

More styles would be great. If you have one you would like to add to the widget
just give me a pull request at the site [page](https://github.com/Kyly/MeWidget).

== Screenshots ==
1. Add to a widget are and enter your Gravatar  account email.
2. Add attributes through the widget panel.
3. Add link to any avatar you like. Customize further by adding attributes.

== Changelog ==

= 1.0.3 =
* Patch to fix wrong email showing in widget.