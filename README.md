Сделать сервис со следующим функционалом:

- Есть возможность авторизоваться через логин-пароль (один пользователь);
- после авторизации увидеть страницу, на которой выведены следующие данные:
1. Дата время добавления записи;
2. Дата время, полученные от https://mgcl.ru/date
4. Хеш (детали ниже), если запись добавлена администратором, иначе n/a;
- Записи добавляются раз в 42 секунды автоматически;
- Есть возможность добавить запись через форму, при этом содержимое поля "дата-время" заполняется значением, полученным от https://mgcl.ru/date и недоступно для редактирования, а содержимое поля "Хеш" выбирается администратором из выпадающего списка, полученного с https://mgcl.ru/showlist. Поле "Хеш" необязательное, по умолчанию имеет значение "manual_hash";
- Страница с данными имеет пагинацию, поиск по интервалу дата-время.

Фреймворк Symfony 5.3+, база данный PostgreSQL 13+, фронтенд Vue.js 2+. Проект должен иметь docker-compose.yml для развертывания.

```
docker-compose up
```
Run inside php-fpm container
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
watch -n 42 php bin/console app:create-product
```
