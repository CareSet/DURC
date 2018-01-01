#!/bin/bash
./artisan DURC:mine --squash --DB=northwind_model --DB=northwind_data --DB=aaaDurctest --DB=irs
./artisan DURC:write --squash
cp routes/durc.php routes/web.php
cat routes/durc_test.php >> routes/web.php
