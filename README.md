# Блог для публикации материалов связаннных с Digital.

## Функциональность
Просмотр всех статей с постраничной навигацией при прокрутке.  
Просмотр выбранной статьи.Фильтр по рубрике или автору.  
Публикация статей доступна после регистрации.  
Личный кабинет пользователя с возможностью добавлять, редактировать, удалять статьи.  
Админпанель для управления содержимым блога.

## Особенности
Авторизация с использованием JWT-токенов.  
Пререндеринг.  
Серверное API выполнено на PHP.

## Технологии используемые при разработке:
- Vue.js
- Vue CLI
- Vuex
- Vue Router
- Vue-resource
- PHP, MySQL
- JWT

## Запуск в режиме разработки
Все папки кроме dist, prerendered, server размещаем в папке вашего проекта на ПК.  
Содержимое папки server размещаем в корне папки на сервере.  

Во vue.config.js и build.prerender.js указываем настройки для proxy: target: "ваш домен (там где находится содержимое папки server".  
В components/page/Article.vue и components/page/cabinet/UpdateAticle.vue пути к img - "для разработки".
```  
Устанавливаем все зависимости: npm install.
```  

На сервере:
- создаем базу данных, импортируем blog.sql.
- в api/config/settings.php указываем данные для подключения к базе данных.

````
npm run serve - запускаем в режиме разработки.
````

## Запуск на сервере 
Содержимое папки server размещаем в корне папки на сервере.  
Во vue.config.js и build.prerender.js указываем настройки для proxy: target: "ваш домен (там где находится содержимое папки server".  
В components/page/Article.vue и components/page/cabinet/UpdateAticle.vue пути к img - "для prodaction".Содержимое dist и папку prerendered размещаем в корне папки на сервере.  
dist и prerendered берем из репозитория или запускаем npm run render, предварительно удалив prerendered.

## Обновление контента и пререндеринг
После обновления контента необходимо выполнить npm run render, предварительно удалив prerendered и обновить папку prerendered на сервере.

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```
### Compiles and minifies for production + prerendering
```
npm run render
```

### Lints and fixes files
```
npm run lint
``` 