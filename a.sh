git add .
git commit -m 'tmp'
git push
git tag -a release-v"$1" -m "$1"
git push origin release-v"$1"