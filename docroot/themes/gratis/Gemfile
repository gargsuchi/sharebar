source 'https://rubygems.org'

group :development do

  # Sass, Compass and extensions.
  gem 'sass', '~>3.4.0.rc.1'      # Sass.
  gem 'sass-globbing', '~>1.1.1'  # Import Sass files based on globbing pattern.
  gem 'compass', '~>1.0.0.rc.1'           # Framework built on Sass.
  gem 'compass-validator'       # So you can `compass validate`.
  gem 'compass-normalize'       # Compass version of normalize.css.
  gem 'compass-rgbapng'         # Turns rgba() into .png's for backwards compatibility.
  gem 'susy'                    # Susy grid framework.
  gem 'toolkit', '~>2.0'         # Compass utility from the fabulous Snugug.
  gem 'breakpoint', '~>2.0'            # Manages CSS media queries.
 # gem 'oily_png'                # Faster Compass sprite generation.
 # gem 'css_parser'              # Helps `compass stats` output statistics.
   gem 'sass-media_query_combiner'

  # Guard
  gem 'guard'                   # Guard event handler.
  gem 'guard-compass', '~> 1.1.0'
  gem 'guard-shell'             # Run shell commands.
  gem 'guard-livereload'        # Browser reload.
  gem 'yajl-ruby'               # Faster JSON with LiveReload in the browser.

  # Dependency to prevent polling. Setup for multiple OS environments.
  # Optionally remove the lines not specific to your OS.
  # https://github.com/guard/guard#efficient-filesystem-handling
  gem 'rb-inotify', '~> 0.9', :require => false      # Linux
  gem 'rb-fsevent', :require => false                # Mac OSX
  gem 'rb-fchange', :require => false                # Windows

end
