#!/bin/sh

LCDIR='locale/en/LC_MESSAGES'
MFILE="$LCDIR/messages.po"

echo "getting strings ... "
xgettext -L PHP --keyword=_ index.php tpl/* --output=$MFILE
sed -i -e 's/CHARSET/UTF-8/' $MFILE
sed -i -e 's!FULL NAME <EMAIL@ADDRESS>!Bill Shupp <hostmaster@shupp.org>!' $MFILE
sed -i -e 's!LANGUAGE <LL@li.org>!Toaster Translators <toaster-tr@shupp.org>!' $MFILE
echo "compiling english ... "
(cd $LCDIR ; msgfmt messages.po)

for i in es ro de ; do
    echo "merging $i ... "
    LCDIR="locale/$i/LC_MESSAGES"
    mv $LCDIR/messages.po $LCDIR/messages.po.old
    msgmerge -o $LCDIR/messages.po \
        $LCDIR/messages.po.old \
        locale/en/LC_MESSAGES/messages.po
    echo "compiling $i ... "
    (cd $LCDIR ; msgfmt -v --check messages.po)
done
