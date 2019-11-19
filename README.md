# Docker for artisans 

"Quick" intro to use Docker in combination with laravel
 
> Slides are available here: https://de.slideshare.net/RenPardon/docker-for-artisans

## example 1

    cd example1 && \
    sh run.sh
    
## example 2

    cd example2 && \
    sh run.sh
    
## example 3

In addition to the laravel application which gets created through the run.sh script, we created those files and directories:

- docker/web/000-default.conf
- docker/crontab/crontab
- docker/crontab/docker-entrypoint.sh
- .dockerignore
- docker-compose.yml
- Dockerfile
- Dockerfile.dev
- run.sh

I also created an email, event and event listener to demonstrate functionality of the worker container.

    cd example3 && \
    sh run.sh

The .env file contains a few adjustments to work nicer with the docker environment:

    APP_NAME="Docker for Artisans"
    APP_URL=http://localhost:8082
    
    LOG_CHANNEL=stderr
    
    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=secret
    
    BROADCAST_DRIVER=redis
    CACHE_DRIVER=redis
    QUEUE_CONNECTION=redis
    SESSION_DRIVER=redis
    SESSION_LIFETIME=120
    SESSION_ENCRYPT=true
    
    REDIS_HOST=redis
    
    MAIL_DRIVER=smtp
    MAIL_HOST=maildev
    MAIL_PORT=25
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_NAME="BoonWeb"
    MAIL_FROM_ADDRESS="rene.pardon@boonweb.de"

> Don't use Apache for Worker/Cron containers - it's just useless overhead!

### Testing events/emails with queues

First go into the database container: `docker-compose exec db bash`
And connect to MariaDB: `mysql -uroot -psecret`
Then create the database schema: `create database laravel;`

Now you can run migrations:

    docker-compose exec app bash
    
    # Inside the App container:
    php artisan queue:table
    php artisan migrate

Then open tinker:

    php artisan tinker

#### And create a new mail

    $mailer = app()->make(Illuminate\Mail\Mailer::class);
    $mail = new App\Mail\TestMail();
    $mailer->queue($mail);
    
#### Or test with an event

    event(new App\Events\TestEvent);


## Build for production

Just an example with default `Dockerfile`:

    docker build -t links-der-isar/docker-for-artisans:1.3.945 .