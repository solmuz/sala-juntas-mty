FROM nginx:alpine
RUN apk add --no-cache nodejs
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY sala-juntas-mty.html /usr/share/nginx/html/index.html
COPY proxy.js /app/proxy.js
COPY start.sh /start.sh
RUN chmod +x /start.sh
EXPOSE 80
CMD ["/start.sh"]
