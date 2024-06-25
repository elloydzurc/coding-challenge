## Getting Started

1. Run `docker compose build --no-cache` to build fresh images
2. Run `docker compose up -d` to start the container
3. Go to applications terminal by running `docker exec -it coding-challenge-php-1 bash` 
4. Run `php bin/console doctrine:migrations:migrate -n` to create the database tables 
5. Edit the line 29 of `src/Scheduler/LogScheduler.php` to change the number of logs to fetch from `logs.log`
6. Run `php bin/console messenger:consume --all` to run the scheduler and messenger
7. Go to `http://localhost/count` using your web browser or Postman.
