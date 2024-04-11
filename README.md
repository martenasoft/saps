## Встанвлюємо


Клонуємо репозиторий
```shell
# git clone https://github.com/martenasoft/test_from_stfalcon.git
```
Запускаем сборку (версія докеру неважлива)
```shell
# make build
```


Запускаем Docker
```shell
# make up
```

В браузері відкриває посилання (може запуститись не відразу. треба почекати)
https://localhost


## Повинні побачити сторінку Api platform
* Заходимо у розділ api

## Регістрація користувача
* Відкриваємо розділ User (/api/registration), тиснемо на [ Try out ], вказуємо email та password та тиснемо на [ Execute ]
  
## Авторизація
* Відкриваємо розділ Login Check (/api/authentication), тиснемо на [ Try out ], указуємо наш email та password, тиснемо [ Execute ]
* Отримуємо два токена refresh та access, копіюємо останній, тиснем на кнопку [ Authorize ], вставляємо у поле та тиснемо [ Login ] 

## Права доступу
* Для встановлення прав доступу, заходимо в файл api/config/packages/security.yaml . У розділі access_control вказуємо ролі для ресурсів 

## Webclients
* pgAdmin

  host: http://localhost:5050/ 
  
  login: raj@nola.com

  password: admin

* rabbitMq

    host: http://localhost:15673/#/

    user: guest

    password: guest
