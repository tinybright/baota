#移动nginx/
#\cp -rf /root/tool/ar-site-new-auto.php /www/wwwroot/wordpress/wp-admin/network/
#\cp -rf /root/tool/ar-set-options.php /www/wwwroot/wordpress/
#\cp -rf /root/tool/class-wp-xmlrpc-server.php /www/wwwroot/wordpress/wp-includes/
#\cp -rf /root/tool/plugins/* /www/wwwroot/wordpress/wp-content/plugins/
#\cp -rf /root/tool/themes/* /www/wwwroot/wordpress/wp-content/themes/
#chmod 777 -R /www/wwwroot/wordpress/wp-content/plugins
#chmod 777 -R /www/wwwroot/wordpress/wp-content/themes
#sh /root/tool/install_swoole.sh
echo "COPY START"
\cp -rf /pub/tool/holder/server/nginx/vhost/holder.conf /www/server/panel/vhost/nginx/
\cp -rf /pub/tool/holder /www/wwwroot/
echo "SUCCESS"
