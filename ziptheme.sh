#!/usr/bin/env bash

# Script: zip-theme.sh
# Purpose: Zips the `wordpress_theme` folder into a versioned or timestamped zip file.

# (Optional) Set a version or timestamp:
VERSION="1.0.0"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)

# The zip filename (customize as you like):
ZIP_FILENAME="wordpress-theme-v${VERSION}_${TIMESTAMP}.zip"

# The folder to zip:
THEME_FOLDER="wordpress-theme"

echo "Zipping ${THEME_FOLDER} into ${ZIP_FILENAME} ..."
zip -r "${ZIP_FILENAME}" "${THEME_FOLDER}" \
  -x "*.DS_Store"      `# Exclude Mac DS_Store files` \
  -x "*.git*"          `# Exclude any .git or .gitignore if present` \
  -x "node_modules/*"  `# Exclude node_modules if present in the theme` \
  -x "package-lock.json" `# Example: exclude potential extra files`

echo "Done! Created ${ZIP_FILENAME}."
