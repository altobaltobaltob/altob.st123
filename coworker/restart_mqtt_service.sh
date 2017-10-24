# 重開

# 0. kill pid
kill -9 `cat mqtt_service_pid.txt`

# 1. start
/usr/bin/php /home/bigbang/apps/coworker/mqtt_service.php > /tmp/mqtt_service.log 2>&1&
echo $! > mqtt_service_pid.txt