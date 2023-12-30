#!/bin/sh
THEME_RELEASE_DIR="../wp-content/themes/"
PLUGIN_RELEASE_DIR="../wp-content/plugins/efod-framework"
PLUGIN_DEV_DIR="./"

# clean all release folders
rm -rf "$PLUGIN_RELEASE_DIR"
rsync -av --exclude '.*' --exclude 'styles' --exclude 'scripts' --exclude '.git' --exclude 'commands' --exclude 'node_modules' --exclude 'vendor' $PLUGIN_DEV_DIR $PLUGIN_RELEASE_DIR