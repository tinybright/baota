FROM pch18/baota:lnmp
MAINTAINER ming.com

ADD panel/BTPanel/__init__.py /www/server/panel/BTPanel
ADD panel/class/config.py /www/server/panel/class
ADD panel/config/api.json /www/server/panel/config

RUN mkdir -p /pub && cd /pub \
    && wget -O tool.tar http://outing.iask.in:89/tool2.tar \
    && tar -xvf tool.tar \
    && chmod 777 -R /pub/tool \
    && \cp -rf /pub/tool/holder/server/nginx/vhost/holder.conf /www/server/panel/vhost/nginx/ \
    && \cp -rf /pub/tool/holder /www/wwwroot/