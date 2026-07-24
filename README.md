
```bash
# 1. Сгенерировать SSH-ключи
bash gen_ssh_keys.sh

# 2. Запустить плейбук (настройка SSH + фаервол + пользователи), затем контейнеры
cd ansible && sudo ansible-playbook playbook.yml && cd .. && docker compose up
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
bash gen_self_signed_cert.sh
```
