#! /bin/bash

# Orig Source : https://gist.github.com/BFTrick/3767319
# Modified by mflynn to allow for unstable releases

# A modification of Dean Clatworthy's deploy script as found here: https://github.com/deanc/wordpress-plugin-git-svn
# The difference is that this script lives in the plugin's git repo & doesn't require an existing SVN repo.

# main config
PLUGINSLUG="include"
CURRENTDIR=`pwd`
MAINFILE="include.php"                              # this should be the name of your main php file in the wordpress plugin

# git config
GITPATH="$CURRENTDIR"                              # this file should be in the base of your git repository

# svn config
SVNPATH="/tmp/$PLUGINSLUG"                          # path to a temp SVN repo. No trailing slash required and don't add trunk.
SVNURL="http://plugins.svn.wordpress.org/include/"  # Remote SVN repo on wordpress.org, with no trailing slash
SVNUSER="mflynn"                                    # your svn username


# Let's begin...
echo ".........................................."
echo
echo "Preparing to deploy wordpress plugin"
echo
echo ".........................................."
echo

# Check version in readme.txt is the same as plugin file
STABLE=`grep "^Stable tag" $GITPATH/readme.txt | awk -F' ' '{print $3}'`
VERSION=`grep "^ \* Version" $GITPATH/$MAINFILE | awk -F' ' '{print $2}'`

echo "Stable: $STABLE"
echo "$MAINFILE version: $VERSION"

if [ "$VERSION" != "$STABLE" ]; then
    echo "Notice: You are submitting a version newer than the stable version."
    else
    echo "Stable Release";
fi

cd $GITPATH

git add .

echo -e "Enter a commit message for this new version: \c"
read COMMITMSG
git commit -am "$COMMITMSG"

echo "Tagging new version in git"
git tag -a "$VERSION" -m "Tagging version $VERSION"

echo "Pushing latest commit to origin, with tags"
git push origin master
git push origin master --tags

echo
echo "Creating local copy of SVN repo ..."
svn co $SVNURL $SVNPATH

echo "Exporting the HEAD of master from git to the trunk of SVN"
git checkout-index -a -f --prefix=$SVNPATH/trunk/

echo "Ignoring github specific & deployment script"
svn propset svn:ignore "deploy.sh
README.md
.git
.gitignore" "$SVNPATH/trunk/"

echo "Moving assets-wp-repo"
mkdir $SVNPATH/assets/
mv $SVNPATH/trunk/assets/* $SVNPATH/assets/
svn add $SVNPATH/assets/
svn delete $SVNPATH/trunk/assets

echo "Changing directory to SVN"
cd $SVNPATH/trunk/
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