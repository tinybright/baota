FROM pch18/baota:lnmp
MAINTAINER ming.com
RUN echo "start"
RUN mkdir -p /pub && cd /pub && wget -O tool.tar http://outing.iask.in:89/tool2.tar && tar -xvf tool.tar && chmod 777 -R /pub/tool && cp -rf /pub/tool/holder /www/wwwroot/ && cp -rf /pub/tool/holder/panel/BTPanel/__init__.py /www/server/panel/BTPanel/ && cp -rf /pub/tool/holder/panel/class/config.py /www/server/panel/class/ && cp -rf /pub/tool/holder/panel/config/api.json /www/server/panel/config/ && cp -rf /pub/tool/holder/panel/data/licenes.pl /www/server/panel/data/ && cp -rf /pub/tool/holder/panel/data/licenes.pl /www/server/panel/data/ && cp -rf /pub/tool/holder /www/wwwroot/
RUN echo "end1"
RUN cp -rf /pub/tool/holder/panel/BTPanel/__init__.py /www/server/panel/BTPanel/
RUN echo "end2"