# CakePHP BugTracker Lite

## Запуск

Для работы необходим docker. Вся сборка происходит через docker-compose. Для корректной работы docker-compose нужно
создать файл `docker/.env` из `docker/.env.dist`

```bash
cp docker/.env.dist docker/.env
```

Далее для запуска нужно выполнить команду:

```bash
make d
```

Будет поднят докер и nginx будет слушать порт 8089.

Далее нужно накатить миграции:

```bash
make migrations
```

Для удобства нужно прописать домен в /etc/hosts

```bash
sudo echo "127.0.0.1 test-cake.local" >> /etc/hosts
```

Далее сайт доступен по `http://test-cake.local:8089/`

## MySQL

Для настройки доступов MySQL можно изменить файл `docker/.env` и перезапустить контейнеры

По-умолчанию

```bash env
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=cake
MYSQL_USER=cake
MYSQL_PASSWORD=cake
```

## Вопросы

Вопросы:

* работа с миграциями - flow? `Создаешь таблицы, а после по ним генерация всего`
* почему нету генераций getter/setter?
* каким компонентом нужно пользоваться для авторизации/аутентификации?
    * Пока не совсем понял как разграничивать доступ
* Отдельно компоненты я не делал, так как в текущем проекте они не повторяются, и выноситьсмысланет
* В тестах много dry. Но пока что я считаю лучше так оставить. А оптимизировать после работы с новыми требованиями
* github actions не стал юзать
* Плагин phpstorm для подсказок, а так же ссылок на объекты
