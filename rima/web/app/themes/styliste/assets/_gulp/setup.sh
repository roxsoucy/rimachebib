#!/bin/bash

echo
echo "========================================"
echo
echo "               ASSETS                "
echo
echo "========================================"
echo

deps=(
  bower@^1.7
  bower-installer@^1
  gulp@^3
  gulp-sass@^2.1.0
  gulp-watch@^4
  gulp-plumber@^1
  gulp-rename@^1
  gulp-autoprefixer@^3
  gulp-uglify@^1
  gulp-minify-css@^1
  browser-sync@^2
  gulp-debug@^2
  requirejs@^2.1
  gulp-requirejs-optimize@^0.3.0
)


# ACTIONS
action="install"
if [ $1 ]
then
  action=$1
fi

#
#
# INSTALL PACKAGES
function install_package() {
  local package=$1
  local version_asked=$2
  local current_version=`npm ll -pg --depth=0 $package | grep -o "@.*:" | sed 's/.$//; s/^.//'`
  if [[ $current_version ]]
    then
      echo "$package found!"
      echo $current_version
      echo "Creating symlink for global $package"
      npm link $package
    else
      echo "$package not found."
      echo "Installing $package@$version_asked"
      npm install "$package@$version_asked" -g
      echo "Creating symlink for global $package"
      npm link $package
  fi
  echo "-------------------------------------"
}


#
#
# UPDATE PACKAGES
function update_package() {
  local package=$1
  local version_asked=$2
  local current_version=`npm ll -pg --depth=0 $package | grep -o "@.*:" | sed 's/.$//; s/^.//'`
  if [[ $current_version ]]
    then
      echo "$package found!"
      echo $current_version
      echo "Updating $package"
      npm update "$package" -g
      echo "Creating symlink for global $package"
      npm link $package
    else
      echo "$package not found."
      echo "Installing $package@$version_asked"
      npm install "$package@$version_asked" -g
      echo "Creating symlink for global $package"
      npm link $package
  fi
  echo "-------------------------------------"
}


#
#
# UPDATE PACKAGES
function uninstall_package() {
  local package=$1
  local version_asked=$2
  local current_version=`npm ll -pg --depth=0 $package | grep -o "@.*:" | sed 's/.$//; s/^.//'`
  if [[ $current_version ]]
    then
      echo "$package found!"
      echo $current_version
      echo "Unlinking local $package"
      npm link $package
      echo "Uninstalling $package"
      npm uninstall "$package" -g
    else
      echo "$package not found."
      echo "No need for uninstall"
  fi
  echo "-------------------------------------"
}



#
#
# INSTALL / UPDATE BOWER DEPS
function install_bower_deps() {

  echo
  echo "--- Updating Bower dependencies to match requireJS.json file"
  (cd rjs; node actions.js)

  echo
  echo "--- Installing Bower dependencies"
  (cd rjs; bower update)

  echo
  echo "--- Moving main files with Bower-installer"
  (cd rjs; bower-installer)
}

#
#
# INIT
# ===================================================

if [[ $action =~ ^(install|update|uninstall|bower)$ ]]
then

  echo
  echo "          $action packages              "
  echo "----------------------------------------"
  echo


  IFS="@"
  for i in "${!deps[@]}"
  do
    package=(${deps[i]})
    case $action in
      update)
        update_package ${package[0]} ${package[1]}
        ;;
      uninstall)
        uninstall_package ${package[0]} ${package[1]}
        ;;
      install)
        install_package ${package[0]} ${package[1]}
        ;;
    esac
  done

  case $action in
    install)
      install_bower_deps
      ;;
    bower)
      install_bower_deps
      ;;
  esac




#
#
# WRONG ARGUMENT PASSED
# =======================
else

  echo
  echo "Wrong argument given."
  echo

fi



echo
echo "============================ done. ==="
echo

