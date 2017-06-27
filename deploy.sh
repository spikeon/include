#! /bin/bash

# Orig Source : https://gist.github.com/BFTrick/3767319
# Modified by mflynn to allow for unstable releases
# Modified to work with a build directory
# Modified to work with

# A modification of Dean Clatworthy's deploy script as found here: https://github.com/deanc/wordpress-plugin-git-svn
# The difference is that this script lives in the plugin's git repo & doesn't require an existing SVN repo.

# main config
PLUGINSLUG="include"
CURRENTDIR=`pwd`
MAINFILE="include.php"                              # this should be the name of your main php file in the wordpress plugin

# git config
GITPATH="$CURRENTDIR"                              # this file should be in the base of your git repository

BUILDPATH="$GITPATH/build/"
DISTPATH="$GITPATH/dist/"
ASSETPATH="$GITPATH/assets/"

# svn config
SVNPATH="/tmp/$PLUGINSLUG"                          # path to a temp SVN repo. No trailing slash required and don't add trunk.
SVNURL="http://plugins.svn.wordpress.org/include/"  # Remote SVN repo on wordpress.org, with no trailing slash
SVNUSER="mflynn"                                    # your svn username

git add .

while [[ -z "$CHANGELOG" ]]; do
    printf '%s\n' '' '# Changes:' '# Example:' '# * added changelog' > /tmp/deploy_script_changelog.txt
    $EDITOR /tmp/deploy_script_changelog.txt
    CHANGELOG=`grep "^[^#]" /tmp/deploy_script_changelog.txt`
done

COMMITMSG=$CHANGELOG

printf '%s\n' '' '# Changes:' '# Example:' '# * Fixed that major bug guys, sorry'  > /tmp/deploy_script_upgrade.txt
$EDITOR /tmp/deploy_script_upgrade.txt
UPGRADENOTICE=`grep "^[^#]" /tmp/deploy_script_upgrade.txt`

versiony package.json --patch

grunt build

STABLE=`grep "^Stable tag" $BUILDPATH/readme.txt | awk -F' ' '{print $3}'`
VERSION=`grep "^ \* Version" $BUILDPATH/$MAINFILE | awk -F' ' '{print $3}'`

printf '%s\n' "= $VERSION =" "$CHANGELOG" '' "`cat readme/CHANGELOG.md`" > readme/CHANGELOG.md

if [[ ! -z "$UPGRADENOTICE" ]]; then
printf '%s\n' "= $VERSION =" "$UPGRADENOTICE" '' "`cat readme/UPGRADE_NOTICE.md`" > readme/UPGRADE_NOTICE.md
fi

echo "Stable: $STABLE"
echo "$MAINFILE version: $VERSION"

grunt dist

git commit -am "$COMMITMSG"

echo "Tagging new version in git"
git tag -a "$VERSION" -m "Tagging version $VERSION"

echo "Pushing latest commit to portfolio, with tags"

git push origin master
git push origin master --tags

git push portfolio master
git push portfolio master --tags

echo
echo "Creating local copy of SVN repo ..."
svn co $SVNURL $SVNPATH

cd $SVNPATH

svn delete trunk
svn delete assets

cp -r $DISTPATH trunk
cp -r $ASSETPATH assets

svn add trunk
svn add assets


# Add all new files that are not set to be ignored
svn status | grep -v "^.[ \t]*\..*" | grep "^?" | awk '{print $2}' | xargs svn add

echo "committing to trunk"
svn commit --username=$SVNUSER -m "$COMMITMSG"

echo "Updating WP plugin repo assets & committing"
cd $SVNPATH/assets/
svn commit --username=$SVNUSER -m "Updating wp-repo-assets"

echo "Creating new SVN tag & committing it"
cd $SVNPATH
svn copy trunk/ tags/$VERSION/
cd $SVNPATH/tags/$VERSION
svn commit --username=$SVNUSER -m "Tagging version $VERSION"

echo "Removing temporary directory $SVNPATH"
rm -fr $SVNPATH/

echo "*** FIN ***"