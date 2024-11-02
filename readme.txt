=== BigOven Recipe SEO Plugin ===
Contributors: bigoven
Donate link: http://wordpress.bigoven.com/
Tags: recipe,hrecipe,ziplist,grocery,food,recipes
Requires at least: 3.0.1
Tested up to: 4.7.2
Stable tag: 2.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Improve your food blog with rich snippet markup, SEO improvements, save recipe button, ziplist import, prinitng, and more.

== Description ==

* **Enjoy these Benefits**

* **Improve your Google and Bing search-engine presence**: The BigOven Recipe SEO Plugin takes care of the hidden "rich snippets" markup of recipes for Search Engine Optimization (SEO). The plugin automatically marks up your recipes with the preferred format from Google and Schema.org.

* **Import your saved and archived recipes from Ziplist into a new data-manager**: If you're a food blogger who used Ziplist, Transitioning to the BigOven recipe WordPress plugin as your alternative is seamless. The BigOven plugin will import your recipes and change the shortcodes in old posts to the newly updated recipe records in a single click. NOTE-As this plugin modifies previous posts, be sure to back up your MySQL database before you click the option "Import from Ziplist" in the Settings area.

* **Print Button and Print Format**: Each recipe has a "Print" button that brings readers to a handy print-friendly view of your recipe. Readers have the option to print with or without the recipe photo.

* **Three different recipe templates (NEW)**: Display your recipes in one of three different templates that mesh seamlessly with your WordPress theme.

* **Offer readers streamlined "Add to Grocery List" and/or "Save Recipe" mobile features with a terrific companion app**: With the BigOven plugin, you get a "Save Recipe" button on your recipes that allow them to be easily saved to the reader's recipe collection and/or grocery list. Your readers have a chance to carry your recipes with them to the grocery store or kitchen counter with the award-winning [BigOven recipe apps](http://www.bigoven.com/mobile). Full credit and links to your original recipe are preserved.
foodies aware of your writing.  We'll be using this plugin to help our editors scour the web for the the most popular food content.

* **Organize your recipes in a handy dashboard**: Search your recipes by title for easy reference while posting. Your recipes list will live in the "Recipes" area of the WordPress compose dialogue. This makes combining multiple recipes in a post or re-inserting "recipe cards" in posts an easy task.

* **Be eligible for FREE branded promotion**: BigOven will be highlighting recipes found on the web with the "Save Recipe" button enabled. You'll get direct web traffic right to your blog from within our apps, which have been downloaded 13+ million times and are used by millions of cooks each month.

* **Example of a food blog using this plugin**: [On Sugar Mountain](http://www.onsugarmountain.com) - [Homemade Sausage Peppers](http://onsugarmountain.com/2014/09/15/fear-no-food-homemade-sausage-peppers-gnocchi)

== Installation ==

If you're planning to import from Ziplist (enabled by the "Import from Ziplist" button in the Settings area), we strongly URGE you
to first make a backup of your recipe data. The reason is the changes to your blog are irreversible once you make this change, as links to your Ziplist-embedded recipes will be updated to the new format. The old shortcodes used by Ziplist are updated to the new format used by this plugin.

1. Make a backup of your MySQL database.  See [Backing up your Database](http://codex.wordpress.org/Backing_Up_Your_Database)
2. Activate the plugin through the Plugins menu in WordPress
3. Click "Add Recipe" in compose window to add a new recipe.  Enter recipe details there, including featured-image for the recipe.
4. Select preferred recipe display/template
5. Write your blog post, including text, media and more. Where you want to place your recipe, click "Add Recipe".
6. See documentation at [wordpress.bigoven.com](http://wordpress.bigoven.com "Wordpress Recipe SEO plugin")

== Frequently Asked Questions ==

= How much does it cost? =

The plugin is FREE

= When someone clicks "Save Button" what happens? =

When your readers click the “Save Recipe” button, it will launch the BigOven Clipper, which lets any BigOven member save a private copy of
your recipe and/or add to to their grocery list. All clipped recipes saved to BigOven are shown without instructions - BigOven provides a link back
to your blog so you get the full “link juice” and search engine benefits.

See an example here (http://www.bigoven.com/recipe/grilled-chicken-with-pineapple-salsa/860272)

= How do I import from Ziplist? =

First, make a backup copy of your data.  This is because the importer (on the plugin's Settings screen) will update your old posts
to the new format, and you may want to revert changes back if you choose to do so later.

= Where is the data stored? =

In your local Wordpress database, in the wp_postmeta table.

= Does any of my data get sent to BigOven? =

Your recipes are your own.  When cooks decide to save a recipe, BigOven does store pointer snapshot information of your recipe privately,
complete with a link back to your recipe.  Your recipes are, and will always be, your own.  Be sure to read [BigOven's Pledge to Food Bloggers](http://wordpress.bigoven.com).

= Where can I get Help? =

If you have comments, questions or problems, we are here to help.
Contact us at bloggers@bigoven.com
Stay in touch by following us on [Facebook](http://www.facebook.com/bigoven), [Twitter](http://www.twitter.com/bigoven) or on the [BigOven blog](http://blog.bigoven.com/)

== Screenshots ==

1. To add a recipe, simply click "Add Recipe" in the compose window.  If you don't see that, be sure to Activate the Plugin.
2. Rich snippets help your recipes stand out.  This plugin renders your recipes in rich-snippet-compatible format, but it's ultimately
up to Google whether or not to display recipes from your site in Rich Snippets format.  Google reports that they use a variety of "quality signals"
to determine whether or not to render the rich snippet version of the recipe in search results.
3.  The Recipes option on the left hand bar lets you see all your recipes added, including search for them in an easy interface.
4.  When adding a recipe (partial screen shown), you can enter title, ingredients, preparation, yield, preparation time, nutrition information and more.
5.  The settings view lets you choose whether or not to list recipe instructions in numbered form, whether or not to enable the "Save Recipe" button and more.

== Changelog ==
= 2.2.0 
* Bug fix to better support SSL

= 2.1.9
* Source control tag realignment

= 2.1.8
* Editing view improvement

= 2.1.7
* Additional fixes for recipe edit view

= 2.1.6
* Fix for admin seo card, https and latest Wordpress compatibility.

= 2.1.4
* Tested through Wordpress 4.7.3

= 2.1.3
* Fixed image resource to serve over SSL (https) connections

= 2.1.2
* Updated shortcode content embed JavaScript to match format required since WordPress 4.2

= 2.1.1
* Support h-recipe format in addition to hrecipe format

= 2.1.0 =
* Fix for settings persistence of checkbox
* Added output of JSON recipe data when shortcode is embedded in a post/page (for Google cards)

= 2.0.1 =
* Fix for installation bug

= 2.0.0 =
* In recipe instructions, you can now include HTML links, italics and bold via Markdown syntax (see the editor for description)
* Recipe posts now show a placeholder with a handy Edit button in the post itself linking to the recipe
* You can now style a border on your recipe card, style it with width, type and color
* Adding a recipe now defaults to -new- recipe, not find from library

= 1.6.1 =
* Enhancing layout for cook time view

= 1.6.0 =
* Adding a new theme at blogger request - cook time stats just below title

= 1.5.01 =
* Adding a couple missing files for 1.5 release

= 1.5.0 =
* You can choose from three design layouts in Settings area: Compact, Basic and Grey
* Two layouts in print preview
* Blank ingredient-lines are not displayed (no more empty bullets)
* New graphics for save and print

= 1.1.2 =
* Adjustment of print url to fit special cases

= 1.1.1 =
* Additional files required for print

= 1.1.0 =
* Added print support!  (More styling options coming in future free updates)
* Changed Save button formatting to CSS
* Slightly rounded corners on recipe hero images

= 1.0.9 =
* removed box-shadow (if present due to parent styling) on button

= 1.0.8 =
* cursor on button hover changes to pointer

= 1.0.7 =
* cleaner button design

= 1.0.6 =
* Remove common hook (was interfering with other common plugins)

= 1.0.5 =
* Clarifying improvements to text in control panel

= 1.0.4 =
* You can now put an exclamation point at the start of an ingredient line and it'll be treated as a heading.  Example:

!For the Frosting
1/2 cup butter
1 cup sugar
etc.
!For the Cake
2 cups flour
etc.

= 1.0.2 =
* Readme update

= 1.0.1 =
* Readme improvements, screenshots

= 1.0 =
* Major new release supporting Import from Ziplist, recipe SEO rich snippet markup, Save recipe / Save Grocery list features.

== Upgrade Notice ==

= 1.0 =
Initial release.