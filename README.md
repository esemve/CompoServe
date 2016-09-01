# CompoServe

**What is this?**

CompoServe is a git repository 2 composer package generator. With its help you can serve the content of the git repository available in the file system as a package.

# Settings

**Dependency:**

With the CLI php exec it should be able to start the git command. Besides that it is important that the user from which you start the generating has reading rights to the folder that contains your git repository.

**Installation**

1. Clone the project or install from composer
```bash
composer require esemve/composerve --prefer-dist
```
2. Make available the content of the public folder online
3. Transfer all queries that are arrive to public to index.php (this way it can serve the packages.json request as well)

**Settings**

Rename config/repositories.example.php to repositories.php, and add the packages and their git folders to it.

# Usage

With the help of composerve.php in the root you can access the console. Run the command below after you modified your package (or added a new package):

```bash
  php composerve.php build
```
It goes through all of the added repositories, interprets the tags that are in the git, retrieves the data from the composer.json, and generates zipball to the versions.

Where you want to use the packages served by CompoServe, add it to the  composer.json:

```json
"repositories": [
    {
        "type": "composer",
        "url": "http:\/\/composerve.yourdomain.com"
    }
],
```
... where the http://composerve.yourdomain.com is the url through which the content of the /public folder is available. 

From that point the 
```bash
composer update
```
command has access to all of the packages, it can download any of the versions, just like you have been using the original packagist.org.

# What should be in the git?

The system can work with the git repository formats that are accepted by packagist.com. You can find the description here: https://packagist.org/about

---

If you want to help me fork it and send me a pull request! :)

---

License: MIT

