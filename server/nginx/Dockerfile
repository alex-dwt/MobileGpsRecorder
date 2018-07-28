FROM nginx:1.11-alpine

RUN apk add --update nodejs

COPY config /etc/nginx/

CMD ["sh",  "-c", "/etc/nginx/boot.bash"]