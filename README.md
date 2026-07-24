
```bash
# 1. Сгенерировать SSH-ключи
bash gen_ssh_keys.sh

# 2. Запустить плейбук (настройка SSH + фаервол + пользователи), затем контейнеры
cd ansible && sudo ansible-playbook playbook.yml && cd .. && sudo docker compose up
```

## Смена порта SSH

```bash
cd ansible && sudo ansible-playbook playbook.yml -e ssh_port=2222
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

---

## Известная проблема: UFW + Docker

Docker публикует порты через собственные правила iptables в обход UFW. Поэтому порты 80, 443, 3000 (Grafana), 3100 (Loki) доступны извне, даже если ufw настроен на `default deny incoming`.

**`"iptables": false` в `/etc/docker/daemon.json` не подходит** — без iptables у Docker ломается сетевая изоляция контейнеров и маршрутизация к внешним сетям.

**Решение** — использовать цепочку `DOCKER-USER` (её правила обрабатываются до правил Docker):

```bash
# Запретить весь входящий трафик к опубликованным портам Docker (кроме localhost)
iptables -I DOCKER-USER -i eth0 -m conntrack --ctstate NEW -j DROP

# Разрешить только нужные порты
iptables -I DOCKER-USER -i eth0 -p tcp --dport 80 -j ACCEPT
iptables -I DOCKER-USER -i eth0 -p tcp --dport 443 -j ACCEPT
```

Либо заблокировать служебные порты (Grafana, Loki) с localhost:

```bash
iptables -I DOCKER-USER -i eth0 ! -s 127.0.0.1 -p tcp --dport 3000 -j DROP
iptables -I DOCKER-USER -i eth0 ! -s 127.0.0.1 -p tcp --dport 3100 -j DROP
```

Правила не сбрасываются при перезапуске Docker. Можно оформить как Ansible-таск или systemd-юнит для автоматизации.
