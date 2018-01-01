# DURC
DURC is reverse CRUD

DURC is an artisan console command that builds Laravel Eloquent models and views by reading from the database assuming the DB follows some rules.

DURC:mine will mine your databases using the structural rules listed below, and generate a configuration file.
DURC:write will use the configuration file to generate basic CRUD components for a Laravel system.

## Installation

Via Composer

```bash
$ composer require careset/durc:dev-master
```

Eventually we will release a stable, non-alpha version. But for now you will have to accept alpha code.

This project only works on Laravel 5.5

[package auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518) should work..

Publish the configuration of DURC and laravel-handlebars

```bash
$ php artisan vendor:publish --provider="CareSet\DURC\DURCServiceProvider"
$ php artisan vendor:publish --tag=laravel-handlebars
```
### Add stuff to your config files

Add the following lines to yourproject/config/app.php
under service providers:

```
        ProAI\Handlebars\HandlebarsServiceProvider::class,
        CareSet\DURC\DURCServiceProvider::class,
```

DURC mustache templates use the .mustache extension. 
Now add the '.mustache' to the 'filext' parameter array in yourproject/config/handlebars.php
This will ensure that you can see the views that are generated.

## Available commands

**Command:**
```bash
$ php artisan DURC:mine --DB=thefirst_db_name --DB=the_second_db --DB=the_third (etc...)
$ php artisan DURC:write
```


If you want to run a fresh install and demo DURC stuff, load the mysql tables from /test_mywind_database/ and then run
```bash
$ php artisan DURC:mine --DB=northwind_model --DB=northwind_data --DB=aaaDurctest --DB=irs
$ php artisan DURC:write
```

There is a demo web interface which you can see by copying the contents of yourproject/routes/durc.php 
to yourproject/routes/web.php

DURC:mine will generate two files in the /config directory

 yourproject/config/DURC_config.autogen.json
 yourproject/config/DURC_config.edit_me.json

If you change the DURC_config.edit_me.json, it will no longer be overwritten by subsequent DURC:mine runs.

DURC:write will take whatever content exists in DURC_config.edit_me.json and generate the following Laravel Assets from it:

* DURC Controllers under yourproject/app/DURC/Controllers/DURC_*Controller.php (these will always be overwritten by subsequent DURC:write runs)
* Laravel Controllers (that inherit from the corresponding DURC Controllers) under yourproject/app/Http/Controllers/*Controller.php (these are not overwritten by subsequent DURC runs, this is where your code goes)
* DURC Models under yourproject/app/DURC/Models/DURC_*.php
* Laravel Eloquent Models (that inherit from the corresponding DURC models) under yourproject/app/*.php
* Mustache Templates under yourproject/resources/views/DURC/ 
  * Index templates for each table
  * Edit templates for each table
  * A starting menu template that lists all generated tables and demonstrates how to include durc forms using mustache etc
* routes to a route file called yourproject/routes/durc.php that can be copied to /yourproject/routes/web.php
* testing routes to a file called yourproject/routes/durc_test.php 

We use a webpack requirement for this, but you JS system might be different.

https://datatables.net/examples/styling/bootstrap4.html

#How It Works

"side-by-side" generation means that the initial durc generate command will build a something.autogen.ext and a something.editme.ext and 
if (and only if) the something.editme.ext is edited or changed somehow then it will not be overwritten. 
The autogen version will always be overwritten. 

"inherited" generation means that DURC generates a base class, which a user-editable class extends. DURC will always overwrite the parent class
but will never overwrite modified child classes

using the "squash" option on the command line will tell DURC to overwrite everything, even classes that would otherwise contain user changes.
Use this option with caution.  

#Table Syntax Rules

In order to generate the Laravel Eloquent classes automagically, you have to follow a few rules
in how you setup your database. While not all of these rules have an impact in the current
version of DURC, they are all db syntax that we intended to support. 

* Do not use plural names in your tables. DURC is singular only and does not do any transforms between singluar and plural. They are too complicated and do not provide enough benifit.
* You must have AutoIncrement and PRIMARY key set on the 'id' field of each table. This is what DURC will link things to.
* If you want to link a field to the id of another table, then ensure that you end the field name with YourThing_id
* you can have more than one, so Another_YourThing_id and AFine_YourThing_id will both work as expected
* Many to many relationships are essentially ignored, instead DURC will resolve a two many-to-one relationship to the cnetral table. You can override this in your client code if desired. 
* the label for the select element is autogenerated based on preferring the first fields with 'name' or 'label' etc. After that DURC will choose the first text field it can find.  
* However, if there is a field called select_name then the system understands that to be the real right answer and will use that as the select name
* if the fields beigns with is_ then it is regarded as being a boolean and will be replaced with a checkbox or a radio button...

Laravel Eloquent expects the 

    ALTER TABLE `YourThing`
    ADD `created_at` DATETIME NOT NULL,  
    ADD `updated_at` DATETIME NOT NULL,
    ADD `deleted_at` DATETIME DEFAULT NULL

The 'deleted_at' field has to have the capacity to exist as null, and if you have it there, DURC should enable Eloquents Soft Delete methods, for the table. https://laravel.com/docs/5.5/eloquent#soft-deleting 

Because we build function names from the field names in the table, we have to have some limitations.
You can start a field with a digit '1', or '0' but you cannot start a function that way.. so beware...


## ToDo

Read about the power of HTML5 forms as a starting point: https://www.html5rocks.com/en/tutorials/forms/html5forms/


- [x] Traditional CRUD: Generate mostache templates
- [X] Traditional CRUD: Generate controllers that work with server-side form templates 
- [X] Traditional CRUD: Generate menu listing just overwrite style
- [X] Traditional CRUD: Have a object viewer that shows the edit form for an object. 
- [X] Traditional CRUD: Get select2 working to link different object types.
- [X] Traditional CRUD: support select_name
- [ ] Traditional CRUD: Support self referencing tables 
- [ ] Traditional CRUD: Support multiple types of relations to the same underlying table, like parent_person_id and sibling_person_id
- [ ] Traditional CRUD: make sure deleting through the controller works, and that soft delete works properly
- [ ] Traditional CRUD: 
- [ ] API CRUD: Generate Vue.js front end forms for editing
- [ ] API CRUD: Generate API Resource elements in parallel with controller under /DURCAPI/whatever/ https://laravel.com/docs/5.5/eloquent-resources
- [ ] API CRUD: Port mustache form smartness to Vue.js 
- [ ] API CRUD:
- [ ] Traditional CRUD v.2: if there is a deleted_at column, automatically add use SoftDeletes to the laravel model.
- [ ] Traditional CRUD v.2: Get html5 date widget to datetime/timestamp mysql working
- [ ] Traditional CRUD v.2: get url widget working 
- [ ] Traditional CRUD v.2: get email widget working
- [ ] Traditional CRUD v.2: get telephone widget working
- [ ] Traditional CRUD v.2: get color widget working
- [ ] Traditional CRUD v.2: get range widget working 
- [ ] Traditional CRUD v.2: get week widget working
- [ ] Traditional CRUD v.2: get month widget working
- [ ] Traditional CRUD v.2: support input patterns (http://html5pattern.com/Miscs)
- [ ] Traditional CRUD v.2: support validity considerations (i.e. should a field only be accepted when valid?)
- [ ] Traditional CRUD v.2: allow autocomplete configuration
- [ ] Traditional CRUD v.3: Generate menu listing all DURC contents in a overwrite/not overwrite side-by-side style
- [ ] Traditional CRUD v.3: Generate blade templates
- [ ] Traditional CRUD v.3: allow placeholder definition
- [ ] Traditional CRUD v.3: use attribute casting on variables named is_something or has_something  https://laravel.com/docs/5.5/eloquent-mutators#attribute-casting 
- [ ] Traditional CRUD v.3: Respect existing foreign keys and treat them as 'name compliant'







# Todo next
Sort out the sibling table. it has four identical $DURC_selfish_with array contents. Obviously that is wrong. It is happening because it loops back on itself. 
But it does that with two different types of relationships
There is also something funky going on with the northwind tables. http://homestead.test/DURC/product/86/ fails to find a table.

