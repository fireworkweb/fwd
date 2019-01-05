FROM nginx:alpine

WORKDIR /var/www/html

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

COPY h5bp /etc/nginx/h5bp
COPY default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
