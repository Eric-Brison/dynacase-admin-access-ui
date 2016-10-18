pages_html = $(patsubst %.html,$(pubdir)/$(style)/$(appname)/Layout/%.html,$(filter-out $(pages_not_html),$(wildcard *.html)))
pages_json = $(patsubst %.json,$(pubdir)/$(style)/$(appname)/Layout/%.json,$(filter-out $(pages_not_json),$(wildcard *.json)))

include $(utildir)/PubRule

.PHONY: publish

publish: $(pages_html) $(pages_json)

$(pubdir)/$(style)/$(appname)/Layout/%.html : %.html $(pubdir)/$(style)/$(appname)/Layout
	$(installcp) $< $@
$(pubdir)/$(style)/$(appname)/Layout/%.json : %.json $(pubdir)/$(style)/$(appname)/Layout
	$(installcp) $< $@
