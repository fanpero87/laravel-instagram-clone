## This is an Instagram Clone WebApp

URL of the tutorial: https://www.youtube.com/watch?v=ImtZ5yENzgE

Note: Laravel Framework allows you to create a Full stack WebApp. HTML and Vue.js for the FrontEnd and
PHP for the BackEnd.

# Artisan

Is a command line tool that comes with Laravel and will help you to interact with your app
Note: To run any artisan command you have to be on your root folder
-> php artisan <command>
-> php artisan serve => This command will start the Webserver for the App. Just leave the tap open at all times. If you need to run some other command, use another tab of Powershell.

If we want to have some sort of auth in our project, we have to run:
-> php artisan make:auth

If "php artisan make:auth" doesn't work in your version of Laravel, try these 3 commands one after one in your project folder:
1.composer require laravel/ui
2.php artisan ui vue --auth
3.npm install && npm run dev
Note: This will change some views on the project and allow you to use authentication.
Note2: I will explain this further on the FrontEnd section below

This command will help you to create specific policies into your project.
-> php artisan make:policy ProfilePolicy -m Profile

# Front End 

Laravel by default uses Bootstrap (HTML,CSS) and Vue.js but you could use other FrontEnd frameworks such as React or Angular. you could also change Bootstrap for something like tailwind for HTML and CSS

To actually use all FrontEnd components, we have to compile everything first. to do this we need node nad npm.
Once you have them both, run:
1. npm install
2. npm run dev --> This will compile everything on Laravel so that we can use it
3. npm run dev --> usually you will have to run it again
Note: you should see at the end 2 files "app.css" and "app.js"

A way to create JS functions to run on the FrontEnd

Inside Resources\JS\Components\*.vue
Inside Resources\JS\app.js
npm run watch --> use this command to keep compiling all files all the time

# Sqlite DB

For this project, we will use sqlite as DB. Under the database folder run:
vi database.sqlite 
Exit the file. We just needed to create an empty file.
Now, go to the ".env" file, modify a line to "DB_CONNECTION=sqlite" we can delete all other DB lines. 
Finally, we have to run "php artisan migrate" to re-create all tables and save them on the sqlite file.

Update: I changed this, and use a mysql DB locally on the server.

# Migrations

Files that describe the DB. Instead of going to the DB and make modifications manually, we could modify the migrations files instead.
Everytime you make a change, you have to run
-> php artisan migrate:fresh --> This will delete everything and re-create the DB

# Controllers

Under the App folder, controllers is where all the logic of the webapp should be.
The Views folder is just for HTML files, never to add PHP logic or any kind of process.
To create a new Controller, just run on terminal:
-> php artisan make:controller <name>

# Models

A Model represents a table on the DB and one object on the model represents a row on our DB.
To create a new Model or table for the DB, follow:

1. on terminal Run:
   php artisan make:model <name> -m --> With the "m" Flag it will also make a migration
2. Go to database\migrations, open the new table and add the fields that you want:
   $table->unsignedBigInteger('user_id');
    $table->string('caption');
   \$table->string('image');
3. On the terminal run:
   php artisan migrate --> This will create the table at DB level
4. Go to the Models Folder, there you will se a file for each table. In this case you have to add a public function on each file to relate the table with each other. This will create the relationship at Laravel level. Could be something like this:
   public function profile()
   {
   return \$this->hasOne(Profile::class);
   }

In our project we could have different kinds of relationship between the tables
one to one
one to many --> In this case, we need to add a field that works as "foreign key"
many to many --> In this case, we need to create a "pivot" table that relates the two tables

To stablish a "many to many" we need a pivot table.
-> php artisan make:migration creates_profile_user_pivot_table --create profile_user
-> php artisan make:migration <pivot_table_name> --create <first_table>*<second_table>
\*for the "create" flag is important to know that the tables has to be organized alphabetically, with lower case and with "*" to separate them.

# Composer

Composer is the dependecy manager for Laravel. If we need to install any additional package to our prject, on the terminal, we will run commands like:

-> composer require intervention/image --> PHP library for image manipulation

# Images

If you want to work and save images, like the Instagram Clone that we are creating. You will have to run one command to make sure that the images are available to the public.
-> php artisan storage:link
Answer:
The [C:\Programming\myfirstapp\public\storage] link has been connected to [C:\Programming\myfirstapp\storage\app/public].
The links have been created.

# Add a new field to the Register page

1. Go to App\Http\Controllers\Auth\RegisterController.php and add a line onto the validator:
   'username' => ['required', 'string', 'max:255', 'unique:users'],
2. On the same file, add a line to the create function:
   'username' => \$data['username'],
3. Go to Database\Migrations\create_users_table.php and add a line:
   \$table->string('username')->unique();
4. Go to App\Models\User.php and add a line:
   'username',
5. On the terminal, run:
   -> php artisan migrate:fresh --> this will delete and re-create all tables. Also delete all users

# Tinker

It's a tool would help us to interact with the WebApp on the background.
You have to run this on Powershell as admin and remember to be on the root folder of the project. Now, to "access the app" run:
-> php artisan tinker
Once you are inside, you could run commands like:

> > > User::all(); --> This will show all users on your app

> > > $profile = new \App|Models\Profile(); --> This will fillout the profile table for a user
> > > $profile->title = "some Title";
> > > $profile->description = "some description";
> > > $profile->user_id = <user_id>;
> > > \$profile->save();

> > > $profile->user --> This will show the profile for a user
> > > $user = App\User::find(1); --> This will find a user

> > > \$user->push(); --> This will update all info into the DB

# Vue

We are going to use Vue to create and set up the "follow" button.
We do it this way so that when you click on it, it won't reload the whole site.

Under \Resources\js there is "app.js", here we need to add all .vue files that we create.
Those files will be inside the "components" folder. 

Note: The basic format of the file should be like this:
<template>
    <div>
    </div>
</template>
You can only have 1 DIV and everything that you need, needs to be there.

# Axios

To make APIs request into your App. 
Since the "follow" button is a Js method on Vue, we will need to use Axios to make requests. 

# Telescope

Inside look into the Application
https://laravel.com/docs/8.x/telescope#introduction

# Mail

To send or receive emails to test this WebApp, we are going to use "mailtrap"
URL: https://mailtrap.io

Add the credentials on the ".env" folder
You could instal the Laravel complement for Email with Markdown capabilities

-> php artisan make:mail NewUserWelcomeMail -m emails.welcome-email
