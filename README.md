
PRO224 - Dự Án Tốt Nghiệp (TKTW-PHP Framework)

Website Bán Áo Thể Thao Five Brother

# khởi chạy dự án :

# install app's dependencies

$ install php >= 8.2
$ install node >=18

# install app's dependencies

$ composer install

$ cp .env.example .env

# edit connect database

DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=root

### Next step

```bash
# in your app directory
# generate laravel APP_KEY
$ php artisan key:generate

# run database migration and seed
$ php artisan migrate:refresh --seed

# generate mixing
$ npm install
$ npm run dev

# publish storage
$ php artisan storage:link
dd(1);
