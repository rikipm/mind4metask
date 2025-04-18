**Дисклеймер:** 2 часть вашего задания сформулирована очень криво и в реальной работе я бы не стал начинать её
реализацию без уточняющих вопросов. Но так как это тестовое я решил не отвлекать HR лишними вопросами.

# Развёртывание приложения

1. Склонируйте проект.
2. `cp compose.example.yaml compose.yaml`
3. (Опционально) Если ваши UID:GID отличается от 1000:1000 то в `compose.yaml:services>php>build>docker>args` замените
   их на свои. Это необходимо
   из-за сложностей с volume докера и пермишеннами.
4. `cp src/.env.example src/.env`
5. Сгенерируйте пароль для БД (например с помощью `openssl rand -base64 12`). Установите его в
   `compose.yaml:services>postgres>environment>POSTGRES_PASSWORD` и `src/.env:DB_PASSWORD`
6. `docker compose up`
7. `docker compose exec php php artisan key:generate`
8. `docker compose exec php php artisan migrate`
