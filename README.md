# DURC
DURC is reverse CRUD

Builds Laravel Eloquent models and views by reading from the database assuming the DB follows some rules.

## Installation

Via Composer

```bash
$ composer require careset/durc
```

This project only works on Laravel 5.5 and above.

[package auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518) should work..

Optional you can publish the configuration to provide an own service provider stub.

```bash
$ php artisan vendor:publish --provider="CareSet\DURC\DURCServiceProvider"
```

## Available commands

**Command:**
```bash
$ php artisan DURC:mine --DB=thefirst_db_name --DB=the_second_db --DB=the_third (etc...)
$ php artisan DURC:write
```

DURC:mine and DURC:write are two steps to allow for a auto-generated durc_conf.json file to be generated in config

This project requires datatables for bootstrap 4 to be available in the html
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

## ToDo

Read about the power of HTML5 forms as a starting point: https://www.html5rocks.com/en/tutorials/forms/html5forms/


- [x] Traditional CRUD: Generate mostache templates
- [ ] Traditional CRUD: Generate blade templates
- [X] Traditional CRUD: Generate controllers that work with server-side form templates 
- [ ] Traditional CRUD: Generate menu listing all DURC contents in a overwrite/not overwrite side-by-side style
- [X] Traditional CRUD: Generate menu listing just overwrite style
- [ ] Traditional CRUD: Have a object viewer that shows the edit form for an object. 
- [ ] Traditional CRUD: Get select2 working to link different object types.
- [ ] Traditional CRUD: Get html5 date widget to datetime/timestamp mysql working
- [ ] Traditional CRUD: get url widget working 
- [ ] Traditional CRUD: get email widget working
- [ ] Traditional CRUD: get telephone widget working
- [ ] Traditional CRUD: get color widget working
- [ ] Traditional CRUD: get range widget working 
- [ ] Traditional CRUD: get week widget working
- [ ] Traditional CRUD: get month widget working
- [ ] Traditional CRUD: support input patterns (http://html5pattern.com/Miscs)
- [ ] Traditional CRUD: support validity considerations (i.e. should a field only be accepted when valid?)
- [ ] Traditional CRUD: allow autocomplete configuration
- [ ] Traditional CRUD: allow placeholder definition
- [ ] Traditional CRUD: 
- [ ] Traditional CRUD: 
- [ ] Traditional CRUD: 
- [ ] API CRUD: Generate Vue.js front end forms for editing
- [ ] API CRUD: Generate API Resource elements in parallel with controller under /DURCAPI/whatever/ https://laravel.com/docs/5.5/eloquent-resources
- [ ] API CRUD: Port mustache form smartness to Vue.js 
- [ ] API CRUD:
- [ ] API CRUD:
- [ ] API CRUD:
- [ ] API CRUD:







