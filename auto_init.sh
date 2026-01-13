#!/bin/bash
# Czekamy na sieć
sleep 5
# Odpalamy nasz skrypt PHP (który już jest w obrazie Presty)
php /init_db.php
# Odpalamy sklep
exec /tmp/docker_run.sh
