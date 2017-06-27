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

while [[ -z "$COMMITMSG" ]]; do
    printf '%s\n' '' '# Commit Message' '# Lines starting with asterix are placed in the changelog' '# Lines starting with + are placed in the Upload Notices' > /tmp/deploy_script_changelog.txt
    $EDITOR /tmp/deploy_script_changelog.txt
    COMMITMSG=`grep "^[^#]" /tmp/deploy_script_changelog.txt`
done

CHANGELOG=`echo "$COMMITMSG" | grep "^[*]"`
UPGRADENOTICE=`echo "$COMMITMSG" | grep "^[+]"`


versiony package.json --patch

grunt build

git add .

STABLE=`grep "^Stable tag" $BUILDPATH/readme.txt | awk -F' ' '{print $3}'`
VERSION=`grep "^ \* Version" $BUILDPATH/$MAINFILE | awk -F' ' '{print $3}'`

if [[ ! -z "$CHANGELOG" ]]; then
    printf '%s\n' "= $VERSION =" "$CHANGELOG" '' "`cat readme/CHANGELOG.md`" > readme/CHANGELOG.md
fi

if [[ ! -z "$UPGRADENOTICE" ]]; then
    printf '%s\n' "= $VERSION =" "$UPGRADENOTICE" '' "`cat readme/UPGRADE_NOTICE.md`" > readme/UPGRADE_NOTICE.md
fi

echo "Stable: $STABLE"
echo "$MAINFILE version: $VERSION"

if [[ ! -z "$1" ]]; then
echo "Making this version stable"
echo "%s/$STABLE/$VERSION/g
w
q
" | ex package.json
fi

grunt build

grunt dist

git add .

grunt clean:prepush

git add .

git commit -am "$COMMITMSG"
