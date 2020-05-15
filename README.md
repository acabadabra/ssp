# ssp

ssp (for simple status page) is a lightweight software intended to inform the users
of acab.io about problems or maintenance on the various services.

It can easily be used by other projects.

## Installation

- Clone this project
- Install twig: composer require "twig/twig:^2.0"
- Create events.sqlite3 file database
  cat db/dump.sql | sqlite3 db/events.sqlite3
- Edit templates files
- Edit controller.inc.php and cahnge credentials

## Use

- Add an event
- You can then edit this event again to add as many messages as you want
- Mark it as resolved when event is over
- Remove event or message if needed

## TODO

- ~~RSS feed~~
- ~~Keep new line on textarea~~
- Use a config file
- Internationalize
- mail alerts on update ?