#
# This file is only needed for Compass/Sass integration. If you are not using
# Compass, you may safely ignore or delete this file.
#
# If you'd like to learn more about Sass and Compass, see the sass/README.txt
# file for more information.
#

# Change this to :production when ready to deploy the CSS to the live server.
environment = :development
#environment = :production

# Location of the theme's resources.
css_dir         = "css"
sass_dir        = "sass"
extensions_dir  = "sass-extensions"
images_dir      = "images"
javascripts_dir = "js"

# Require any additional compass plugins installed on your system.
require 'compass-normalize'
require 'rgbapng'
require 'toolkit'
require 'susy'
require 'sass-globbing'
require 'sass-media_query_combiner'
require 'breakpoint'

# Assuming this theme is in sites/*/themes/THEMENAME, you can add the partials
# included with a module by uncommenting and modifying one of the lines below:
#add_import_path "../../../default/modules/FOO"
#add_import_path "../../../all/modules/FOO"
#add_import_path "../../../../modules/FOO"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = (environment == :development) ? :expanded : :compressed

#Chrome web inspector sass debugging
sass_options = {:sourcemap => true}
enable_sourcemaps = true

# To enable relative paths to assets via compass helper functions. Since Drupal
# themes can be installed in multiple locations, we don't need to worry about
# the absolute path to the theme from the server root.
http_path = "/"
relative_assets = true
disable_warnings = true

# Conditionally enable line comments.
#Ideally keep this at false and use FireSass or Chrome source maps.
# see: http://thesassway.com/intermediate/using-source-maps-with-sass
line_comments = false


# Removing all comments by applying a monkey patch to SASS compiler
require "./remove-all-comments-monkey-patch"
