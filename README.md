
```bash
# 1. Сгенерировать SSH-ключи для admin-user и deploy-user
ssh-keygen -t ed25519 -f ansible/roles/users/files/admin-key -N "" -C "admin-user@$(hostname)" && ssh-keygen -t ed25519 -f ansible/roles/users/files/deploy-key -N "" -C "deploy-user@$(hostname)"

# 2. Установить требуемую Ansible-коллекцию
ansible-galaxy collection install -r ansible/requirements.yml

# 3. Запустить плейбук (настройка SSH + фаервол + пользователи), затем контейнеры
cd ansible && ansible-playbook playbook.yml --ask-become-pass && cd .. && docker compose up
```

## Что делает плейбук

| Роль | Действие |
|---|---|
| `ssh-hardening` | Отключает `PasswordAuthentication`, `PermitRootLogin`, включает вход только по ключам на порту `22` |
| `firewall` | Включает `ufw` с **Zero Trust** (запрещено всё входящее, разрешён только SSH) |
| `users` | Создаёт `admin-user` (с sudo) и `deploy-user` — оба входят **только** по SSH-ключу |

## Файлы ключей

Сгенерированные открытые ключи должны лежать в `ansible/roles/users/files/`:

- `admin-key.pub` — публичный ключ для `admin-user`
- `deploy-key.pub` — публичный ключ для `deploy-user`

Приватные ключи (`admin-key`, `deploy-key`) также попадают в ту же папку. Храните их в безопасности.

## Смена порта SSH

```bash
cd ansible && ansible-playbook playbook.yml --ask-become-pass -e ssh_port=2222
```

## Проверка правил фаервола

```bash
sudo ufw status verbose
```

## Проверка синтаксиса

```bash
ansible-playbook --syntax-check playbook.yml
```

```bash
# Генерация самоподписанного сертификата
mkdir -p ./certs && \
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout ./certs/self-signed.key \
  -out ./certs/self-signed.crt \
  -subj "/C=US/ST=State/L=City/O=Organization/CN=localhost"
```
