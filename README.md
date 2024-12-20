## Задание 1 и Задание 2

Было решено выполнить первое задание с учётом второго. Поэтому база данных представлена в виде 4-х таблиц "order", "ticket", "event", "ticket_type". Выставлены зависимости ключей в таблицах. 

Такая структура обусловлена тем, чтобы разбить данные по их смысловым единицам и выставить зависимые значения в соответствующем порядке.

## Задание 3: Документация

Приложение реализовано в виде 2-х Docker контейнеров: 

1) Сервер nginx + php 8.4.
2) База данных MySQL MariaDB

1. Контроллер OrderController - вызывает метод process главного сервиса BookService
2. Сервис BookService:
    1) Содержит главный метод process, который вызывает другие сервисы для отправки данных на стороннее API и повторной генерации barcode.
    2) Содержит метод saveData, который сохраняет OrderDTO в случае получения положительных ответов со стороннего API.
3. Метод process
    1) Находит событие, которое было отправлено в наше приложение.
    2) Формирует order.
    3) Вызывает цикл, который просматривает все билеты, отправленные в запросе и формирует коллекцию всех билетов(с указанием их типа), которые добавляются в order.
    4) Вызывает метод bookOrder, который отправляет order на стороннее API и пересоздаёт barcode, в случае ошибки, вернувшейся с API.
    5) Вызывает метод approveOrder сервиса ApiDataService.
    6) В случае получения ошибки при подтверждении заказа(выполнение метода approveOrder) прерывает процесс и возвращает соответствующую ошибку.
    7) Если все предпроцессы прошли успешно, сохраняет все данные в БД.
3. OrderDTO:
    1) Формирует основной объект данных, пришедших в наше приложение.
    2) Валидирует входящие данные.
    3) Используется в BookService.
4. ApiDataService:
    1) Отправляет данные на стороннее API.
    2) Мокает приходящие данные от API.
    3) Возвращает ответ от API.
5. AppSiteOrderRequest:
    1) Модель запроса к API с учётом её требований на входящие данные.
6. ApiSiteOrderResponse:
    1) Принимает ответ с API.
    2) Содержит метод по проверке успешности запроса к API.
7. ApiSiteApproveResponse:
    1) Принимает результат запроса на подтверждение order.
    2) Содержит метод по проверке успешности запроса к API на подтверждение заказа.

## Комментарии

Структура таблиц обрела именно такой вид, поскольку их более оптимальный вид можно достичь путем изменения структур запросов к сторонней API. 

Также в данных, которые приходят в приложение предполагается user_id, который должен нести за собой логику авторизации или подтверждения аутентификации. Однако такая логика по заданию не предполагается, поэтому приложение предполагает, что такая логика авторизации пользователей уже реализована при приходящих данных.

Структура таблиц предполагает возможность дальнейшего расширения приложения, в случае, если появятся новые типы билетов (за это будет отвечать схема ticket_type).

### Инструкция к запуску

Для запуска приложения необходимо выполнить следующии команды в рабочей директории

```
make start
make composer-install
make load-fixtures
```

Для тестового запуска можно воспользоваться следующим curl запросом

```
docker exec -it nevatrip_test-api-1 curl -X POST http://localhost:8080/api/order -H "Content-Type: application/json" -d '{"event_id": 1, "user_id": 2, "tickets": {"adult": 2, "kid": 8}}'
```
