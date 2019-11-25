# Проект «Giftube» 

## 1. Знакомство с проектом  

  Список отличий для зарегистрированных пользователей:

  1. **На всех страницах у зарегистрированного пользователя есть пользовательское меню:**  
    - никнейм пользователя;  
    - ссылка на страницу избранного;  
    - кнопка выхода.  

  2. **Страница просмотра гифки**. Для зарегистрированного пользователя доступны:  
    - форма добавления комментария;   
    - кнопка "Мне нравится";  
    - кнопка "Добавить в избранное".  

  3. **Кнопка загрузить гифку** работает только для зарегистрированного пользователя.  

  4. **Страница добавления гифки** доступна только для зарегистрированного пользователя.

  5. **Страница избранное** доступна только для зарегистрированного пользователя.  

## 2. Из верстки в шаблоны  

  - создать лейаут(ы);
  - создать шаблоны всех страниц;
  - категории и гифки поместить в массивы и на страницах выводить из массивов.

## 3. Проектируем БД

### 1. Создание схемы БД личного проекта
  Подготовка:

  - Создайте новый файл в корне проекта с именем schema.sql.
  - В этом файле напишите весь SQL-код для создания схемы БД вашего проекта.  

  Требования:

  - весь SQL должен без ошибок выполняться на MySQL 5.7;
  - в каждой таблице должен быть первичный ключ.  

  План работы:

  - Сперва напишите SQL для создания новой базы данных с именем вашего проекта.
  - Напишите SQL для создания всех необходимых таблиц.
  - Добавьте уникальные индексы полям, где должны быть только уникальные значения.
  - Добавьте обычные индексы полям, по которым будет происходить поиск.  

  Как проверить:

  - Запустите HeidiSQL (для Windows) или командную строку MySQL.
  - Попробуйте выполнить ваш файл schema.sql.
  - Файл должен выполниться без ошибок.
  - В БД должны быть таблицы для всех сущностей из ТЗ

  2. Пишем SQL-запросы
