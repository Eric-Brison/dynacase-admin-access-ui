# get the new appname (basename in uppercase)
appname = $(shell basename `pwd` | tr [a-z] [A-Z])

export appname

include $(utildir)/PubRule

$(pubdir)/EXTERNALS :
	mkdir -p $@

$(pubdir)/EXTERNALS/haccess.php: $(pubdir)/EXTERNALS
	cd $(pubdir)/EXTERNALS; \
	ln -sf ../ACCESS/haccess.php haccess.php

publish:$(pubdir)/EXTERNALS/haccess.php