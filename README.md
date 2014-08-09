Me Widget
=========
######License: GPLv2

*Me Widget* provides a simple way to incorporate [Gravatar][] profiles within
your Wordpress, offering easy customizations and straight forward styling.

Description
-----------
*Me Widget* is a widget designed to used with Wordpress 3.5+ sites, and requires
having a [Gravatar][] profile.

####Features
- **_Avatar_**        : Display your Gravatar or a custom avatar.
- **_Profile Info_**  : Display some, all, or none of the personal information
from your [Gravatar][] profile.
- **_Customize_**     : the way your avatar and profile data is displayed using
preset styles.
- **_CSS_**           : Easy to edit or make your own styles adding the style
template to your existing style sheet
- **_Social Icons_**\*  : Displays icons for your verified accounts from your
Wordpress profile.

\*Icons are provided by [Font Awesome 4.1.0][3] created by [Dave Gandy][2], the
font's cheat sheet is found [here] [1], and the list that the widget recognizes
is found [here][4] (_This list may not be complete, if there is a icon that is_
 _in Awesome Fonts that should be included in this list please let me know_).

Installation
------------
_Requirements_: Wordpress 3.9.\* and PHP 5.
1. Download [MeWidget-0.1.1.zip][5]. _(required)_
2. Log into your Wordpress Dashboard then find the `plugin` tab an left
then click `add new`.
3. On the add new page simply click the upload link then click choose file.
Navigate to the MeWidget-0.1.1.zip then press `install`.
4. Once _Me Widget_ is installed activate.

####Setup

* Place the widget into on of your side bars then under the
widget option first put in a `Gravatar Account` email you wish to display then
`save`.
* If the email that was entered is valid you can then begin to
select some of the options.
* A small preview of the data that is contained within each option will be shown
to the right of the check box label. If no data was retrieved for that item it
will be left blank.
* If you would like a image other then the one provided by Gravatar, enter the
the url to the image in the `Custom Image Url` text box.
* With `Image size` you can set the pixel width of the image _(Note: this
is not the same as the display width)_. This option is not available for custom
images.
* You can add attributes by listing them in `Image attributes` text box.
Example: `width=30%, class=thumbnail right-align` gets converted to
`<img src="http://localhost/kylyv/wp-content/uploads/me_graphic.png" width="30%"`
` class="thumbnail right-align">`

###Styling
A few styles have already been provided for you but you most likely want to
make more.

A example CSS has been provided for you in the *Me Wiget* folder.
Simply fill it in with your own styles.

For images you can make your own selectors then add them using the
`Image attributes` dialog.

Here a list of prepackaged styles:

| Selector | Attributes |
|:---------|:-----------|
| right-align | float: right  margin-left: .5em |
| left-align | float: left  margin-right: .5em |

[gravatar]: https://gravatar.com "Gravatar"
[1]: http://fortawesome.github.io/Font-Awesome/cheatsheet/ "Font Awesome Icons"
[2]: https://twitter.com/davegandy "Dave Gandy"
[3]: http://fortawesome.github.io/Font-Awesome/ "Font Awesome"
[4]: https://github.com/Kyly/MeWidget/blob/master/soc_icons_fa "Social Icon List"
[5]: https://github.com/Kyly/MeWidget/archive/v0.1.1.zip "Download Me Widget v0.1.1"
