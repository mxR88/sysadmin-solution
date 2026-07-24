
```bash
# 2. Установить требуемую Ansible-коллекцию
ansible-galaxy collection install -r ansible/requirements.yml

# 3. Запустить плейбук (настройка SSH + фаервол + пользователи), затем контейнеры
cd ansible && ansible-playbook playbook.yml --ask-become-pass && cd .. && docker compose up
```

## Смена порта SSH

```bash
cd ansible && ansible-playbook playbook.yml --ask-become-pass -e ssh_port=2222
```

<!-- ## Проверка правил фаервола -->

<!-- ```bash -->
<!-- sudo ufw status verbose -->
<!-- ``` -->

## Проверка синтаксиса

```bash
ansible-playbook --syntax-check playbook.yml
```

### Использованные команды

```bash
# Генерация самоподписанного сертификата для Nginx
mkdir -p ./certs && \
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout ./certs/self-signed.key \
  -out ./certs/self-signed.crt \
  -subj "/C=US/ST=State/L=City/O=Organization/CN=localhost"

# Генерация SSH-ключtq для admin-user и deploy-user
ssh-keygen -t ed25519 -f ansible/roles/users/files/admin-key -N "" -C "admin-user@$(hostname)" && ssh-keygen -t ed25519 -f ansible/roles/users/files/deploy-key -N "" -C "deploy-user@$(hostname)"
```
