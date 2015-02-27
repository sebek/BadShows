# BadShows

### About
BadShows is a very simple dashboard and API for
finding, adding, creating and deleting shows.

I've used three libraries to build this.

For simple routing:
https://github.com/Respect/Rest

And for some simple database abstraction:
https://github.com/lichtner/fluentpdo

And for generating XML
https://github.com/iwyg/xmlbuilder

### Installing

Clone project
```
git clone https://github.com/sebek/BadShows.git
```

In project root, run composer
```
composer install
```

And also in project root, run the database create and seed script
```
php database/create_and_seed.php
```

### Running
Use the built in server
```
php -S localhost:8000
```

### Demo
Try it out at http://badshows.sebastiankemi.se/
