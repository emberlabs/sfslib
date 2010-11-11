#! /bin/bash

SCRIPT=`dirname $(readlink -f $0)`
$SCRIPT/build-check --src ./src/ --exclude "~$ .*\.txt$ .*\.markdown$ .*\.md$ .*\.json$"
RESULT=`cat $SCRIPT/rebuild`
if [ $RESULT = '1' ]; then
	echo 'updating phar file'
	phar-build --phar $SCRIPT/sfslib.phar --src ./src/ --exclude "~$ .*\.txt$ .*\.markdown$ .*\.md$ .*\.json$"
	$SCRIPT/build-filestate-update --src ./src/ --exclude "~$ .*\.txt$ .*\.markdown$ .*\.md$ .*\.json$"
	cp $SCRIPT/sfslib.phar $SCRIPT/../sfslib.phar
	cp $SCRIPT/sfslib.phar.pubkey $SCRIPT/../sfslib.phar.pubkey
    rm $SCRIPT/sfslib.phar
    rm $SCRIPT/sfslib.phar.pubkey
	rm $SCRIPT/rebuild
	if [ -d $SCRIPT/../.git/ ]; then
		git add $SCRIPT/../sfslib.phar $SCRIPT/filestate.json
	fi
	echo 'success'
else
	rm $SCRIPT/rebuild
	echo 'no rebuild needed'
fi
