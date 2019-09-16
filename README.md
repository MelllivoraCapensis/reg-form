# reg-form
Registration Form

Модели: 
- city
- region
- country
- rubric
- doer_registration (email, phone, city_id, rubrics - строка из рубрик, разделенных '***')
- customer_registration (email, phone, city_id, rubrics - строка из рубрик, разделенных '***')

Форма:
- поля email, phone - валидируются через js, на сервере есть доп. проверка
- поля city, rubric - валидация через сервер, live search + ajax реализован на js native + php
- регистрация и счетчики регистраций реализованы через ajax

Админ:
- слева вверху вход в админку, где можно увидеть списки регистраций и удалить их, логин-пароль admin-admin

Деплой: http://reg-form.tw1.su/

