mkdir -p ./certs && \
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout ./certs/self-signed.key \
  -out ./certs/self-signed.crt \
  -subj "/C=US/ST=State/L=City/O=Organization/CN=localhost"

