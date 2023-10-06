#!/bin/sh
THEME_RELEASE_DIR="../wp-content/themes/"
PLUGIN_RELEASE_DIR="../wp-content/plugins"
PLUGIN_DEV_DIR="./"

if [ -d "$PLUGIN_RELEASE_DIR/efod-neve-plugin" ]; then
  # Dir exists
  # remove dir
  rm -rf "$PLUGIN_RELEASE_DIR/efod-neve-plugin"
fi

rsync -av --exclude 'assets' --exclude 'node_modules' --exclude 'vendor' $PLUGIN_DEV_DIR $PLUGIN_RELEASE_DIR