#!/bin/bash
./artisan DURC --squash --DB=northwind_model --DB=northwind_data --DB=aaaDurctest
cp routes/durc.php routes/web.php
cat routes/durc_test.php >> routes/web.php
