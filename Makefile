all:
	./vendor/bin/phpunit
	cd coverage && git add . && git commit -m "update the coverage" && git push origin --all && cd .. && notify-send "Code coverage updated successfully"
