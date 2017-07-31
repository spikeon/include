# Handle Options

COMMITMSG=""

while getopts ":m:" opt; do
  case $opt in
    m)
      # Sending In msg
      COMMITMSG="$OPTARG"
      ;;

    \?)
      echo "Invalid option: -$OPTARG" >&2
      exit 1
      ;;
    :)
      echo "Option -$OPTARG requires an argument." >&2
      exit 1
      ;;
  esac
done

echo "Starting Unit Tests"

phpunit

if [ $? -ne 0 ]; then
        echo "Failed Unit Test, Don't build."
        exit
fi

echo "Ending Unit Tests"


# main config
PLUGINSLUG="include"
CURRENTDIR=`pwd`
MAINFILE="include.php"                              # this should be the name of your main php file in the wordpress plugin

# git config
GITPATH="$CURRENTDIR"                              # this file should be in the base of your git repository

BUILDPATH="$GITPATH/build/"
DISTPATH="$GITPATH/dist/"
ASSETPATH="$GITPATH/assets/"

while [[ -z "$COMMITMSG" ]]; do
    printf '%s\n' '' '# Commit Message' '# Lines starting with asterix are placed in the changelog' '# Lines starting with + are placed in the Upload Notices' > /tmp/deploy_script_changelog.txt
    $EDITOR /tmp/deploy_script_changelog.txt
    COMMITMSG=`grep "^[^#]" /tmp/deploy_script_changelog.txt`
done

CHANGELOG=`echo "$COMMITMSG" | grep "^[*]"`
UPGRADENOTICE=`echo "$COMMITMSG" | grep "^[+]"`

versiony package.json --patch

grunt build

STABLE=`grep "^Stable tag" $BUILDPATH/readme.txt | awk -F' ' '{print $3}'`
VERSION=`grep "^ \* Version" $BUILDPATH/$MAINFILE | awk -F' ' '{print $3}'`

if [[ ! -z "$CHANGELOG" ]]; then
    printf '%s\n' "= $VERSION =" "$CHANGELOG" '' "`cat readme/CHANGELOG.md`" > readme/CHANGELOG.md
fi

if [[ ! -z "$UPGRADENOTICE" ]]; then
    printf '%s\n' "= $VERSION =" "$UPGRADENOTICE" '' "`cat readme/UPGRADE_NOTICE.md`" > readme/UPGRADE_NOTICE.md
fi

grunt build

grunt dist

grunt clean:prepush

git add .

git commit -am "$COMMITMSG"
