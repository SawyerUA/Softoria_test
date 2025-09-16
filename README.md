# Softoria Test

Простий веб-застосунок для перевірки рангу сайту.

## Як запустити локально

1. Склонуйте репозиторій:
```bash
git clone https://github.com/SawyerUA/Softoria_test.git

2. Переходимо в каталог проекту
cd Softoria_test

3. Встановлюємо залежності Composer
composer install

4. Файл .env.example перейменовуємо в .env і додаємо 2 строки:
DATAFORSEO_LOGIN=ваш_API_email
DATAFORSEO_PASSWORD=ваш_API_password

5. Запускаємо локальний сервер командоню
php artisan serve

6. Переходимо за посиланням або прописуємо в url адресі
http://127.0.0.1:8000
