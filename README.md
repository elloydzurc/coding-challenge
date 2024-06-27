## Getting Started

1. Run `docker compose build --no-cache` to build fresh images
2. Run `docker compose up -d` to start the container
3. Go to applications terminal by running `docker exec -it coding-challenge-php-1 bash` 
4. Run `composer install` to install dependencies
5. Run `php bin/console doctrine:migrations:migrate -n` to create the database tables 
6. Delete `storage/settings.json` (this file stored the current file pointer of the streamed log file) if it's exists.
7. Edit the line 29 of `src/Scheduler/LogScheduler.php` to change the number of logs to fetch from `logs.log`
8. Run `php bin/console messenger:consume async scheduler_default -vv` to run the scheduler and messenger (the first log should appear after approximately 1 minute)
9. Go to `http://localhost/count` using your web browser or Postman.
