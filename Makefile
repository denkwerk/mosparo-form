render-wysiwyg:
	docker run --rm -it --pull always \
	  -v "./Classes:/project/Classes" \
	  -v "./Configuration:/project/Configuration" \
	  -v "./Documentation:/project/Documentation" \
	  -v "./Documentation-GENERATED-temp:/project/Documentation-GENERATED-temp" \
	  -p 5173:5173 ghcr.io/garvinhicking/typo3-documentation-browsersync:latest
	open "http://localhost:5173/Documentation-GENERATED-temp/Index.html"
