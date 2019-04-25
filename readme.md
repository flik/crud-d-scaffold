# Laravel 5.6 Scaffold Generator

  Hi, this is a scaffold generator for Laravel 5.6.
  You can Create Basic CRUD application by using this package.
  
  Basic CRUD application generated by this package has some distinctive features.
  
  (i) Duplicate function.
  (ii) Show sorted list with filter by conditions (include word or range value).


## How to Install

  At first, this package needs [Collective\Html] package.
  See document and Install [Collective\Html].
  
### Step 0: https://github.com/LaravelCollective/docs/blob/5.6/html.md#installation
  
Begin by installing this package through Composer. Run the following from the Terminal:

```
composer require "laravelcollective/html":"^5.6.0"
or 
composer require "laravelcollective/html":"^5.8.0"
```
Next, add your new provider to the providers array of config/app.php:

```
  'providers' => [
    // ...
    Collective\Html\HtmlServiceProvider::class,
    // ...
  ],

```
Finally, add two class aliases to the aliases array of config/app.php:

```
  'aliases' => [
    // ...
      'Form' => Collective\Html\FormFacade::class,
      'Html' => Collective\Html\HtmlFacade::class,
    // ...
  ],
  
```
### Step 1: Install Through Composer

```
composer require dog-ears/crud-d-scaffold
or 
"require": {
    "dog-ears/crud-d-scaffold": "1.*"
}
```

### Step 2: Add the Service Provider

Open `config/app.php` and, to your **providers** array at the bottom, add:

```
dogears\CrudDscaffold\GeneratorsServiceProvider::class
```

### Step 3: Run Artisan!

You're all set. Run `php artisan` from the console, and you'll see the new commands below.
```
- 'make:scaffold' : Create a scaffold with bootstrap 3
- 'delete:scaffold' : Delete a scaffold
- 'make:relation' : Create OntToMany Relationship between model_A and model_B
- 'delete:relation' : Delete OntToMany Relationship between model_A and model_B
```



## Examples 1 - Create Application and make relationship.

(i) publish public resource.
```
php artisan vendor:publish --tag=public --force
```
(ii) Scaffold 2 Model [AppleType] and [Apple].
  Apple has apple_type_id column for relationship.
```
php artisan make:scaffold AppleType --schema="name:string" --seeding
```
```
php artisan make:scaffold Apple --schema="name:string,apple_type_id:integer:unsigned" --seeding
```
(iii) migrate and seeding
```
php artisan migrate
```
```
php artisan db:seed
```
(iv) Make Relationship [AppleType] has many [Apple]s.
```
php artisan make:relation AppleType Apple
```

Check your application.



## Examples 2 Delete Application created scaffold command.

(i) Delete relationship. 
```
php artisan delete:relation AppleType Apple
```
(ii) Delete application.
```
php artisan delete:scaffold AppleType
```
```
php artisan delete:scaffold Apple
```
Some files remains.
  It is recommended that you do migrate:reset and delete files manually.



## Screen Capture
![image](https://github.com/dog-ears/crud-d-scaffold/wiki/img/cap01.jpg)
![image](https://github.com/dog-ears/crud-d-scaffold/wiki/img/cap02.jpg)
![image](https://github.com/dog-ears/crud-d-scaffold/wiki/img/cap03.jpg)
