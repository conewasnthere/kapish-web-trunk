# <img src="https://kapish.fun/images/logos/small.png" width="30"> **Kapish** Website (formerly Tadah)
The tartar sauce spaghetti code website of old legos.

# Setting It Up
## Prerequisites
- Nginx or Apache (Caddy is an option too)
- PHP (Preferably *8.1.14*)
- MariaDB/MySQL
- Redis (Optional)
### PHP Extensions
- Soap
- GD

## Setup
You set this up like any other Laravel site, and then follow the steps below:

1. Run `copy .env.example .env`

After you run this, you have to set up your database connections and a few other things. Then, you will place these in the .env file you just created. 
<br>
⚠️ **Do not fill in `APP_KEY`. Leave it blank.** ⚠️

2. Create a database table, and make sure the name matches what you put in your .env file.

Also make sure the user you put in your .env file has permissions for that database.

3. Run `composer install` to install all the composer packages.

If you get an error, try running `composer update`. It could be outdated package versions messing with your composer install.

4. After following all the steps, the last two things you need to do is run `php artisan key:generate` & `php artisan migrate:fresh --seed`.

The first command generates `APP_KEY`, which is used for encryption. An example is the session cookie, for when you either register for the first time or login. The second command creates all the tables in your database, such as the users table or catalog table.

## Getting The Site Up
After running all the setup commands, it is time to get your site up. Run the following commands:

1. `npm i`

2. `npm run prod`


## Notes
**If you do not wish to use Redis**, you can change the following values in your .env file:
1. Change `CACHE_DRIVER=redis` to `CACHE_DRIVER=file`

2. Change `SESSION_DRIVER=redis` to `SESSION_DRIVER=file`

*---*

**Are you running the site on Apache and routes are returning 404?** Look at this to fix it:
1. Make sure your *.htaccess* in */public/* looks like the following:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

2. Add this in your Apache configuration file:
```apache
<Directory "/var/www/{DIRECTORY}/public">
Allowoverride All
</Directory>
```
*---*

**If your site is showing all the files & directories**, make sure you added /public to the end of your document root.

*---*

⚠️ **If you encounter a 500 error, or something about SoapClient**, you do not have the PHP Soap extension installed.
