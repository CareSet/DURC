#!/bin/bash
./artisan DURC:mine --squash --DB=northwind_model --DB=northwind_data --DB=aaaDurctest
./artisan DURC:write --squash --DB=northwind_model --DB=northwind_data --DB=aaaDurctest
cp routes/durc.php routes/web.php
cat routes/durc_test.php >> routes/web.php
cp ../DURC/test_html/durc_html.mustache resources/views/DURC/
