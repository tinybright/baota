FROM pch18/baota:lnmp
MAINTAINER ming.com
RUN echo "start"
COPY ./tool/holder/panel/BTPanel/__init__.py /www/server/panel/BTPanel/
COPY ./tool/holder/panel/class/config.py /www/server/panel/class/
COPY ./tool/holder/panel/config/api.json /www/server/panel/config/
COPY ./tool/holder/panel/data/licenes.pl /www/server/panel/data/
COPY ./tool/holder/panel/data/licenes.pl /www/server/panel/data/
COPY ./tool/holder/server/nginx/vhost/holder.conf /www/server/panel/vhost/nginx/
RUN echo "end1"