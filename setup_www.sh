#!/bin/bash

current_dir=$(pwd)

www_admin_path=$current_dir/www/www_admin/
www_site_path=$current_dir/www/www_site/

DEFAULT_THEME="default"

# www_admin links setup

read -p "enter WWW_ADMIN theme name [$DEFAULT_THEME]: " THEME_ADMIN
THEME_ADMIN="${THEME_ADMIN:-$DEFAULT_THEME}"

views_admin_path=$current_dir/application/views/$THEME_ADMIN/www_admin

ln -f -s $views_admin_path/css $www_admin_path
ln -f -s $views_admin_path/js $www_admin_path
ln -f -s $views_admin_path/img $www_admin_path

echo "Setup WWW_ADMIN theme to '$THEME_ADMIN' complete"

# www_site links setup

read -p "enter WWW_SITE theme name [$DEFAULT_THEME]: " THEME_SITE
THEME_SITE="${THEME_SITE:-$DEFAULT_THEME}"

views_site_path=$current_dir/application/views/$THEME_SITE/www_site

ln -f -s $views_site_path/css $www_site_path
ln -f -s $views_site_path/js $www_site_path
ln -f -s $views_site_path/img $www_site_path

echo "Setup WWW_ADMIN theme to '$THEME_SITE' complete"