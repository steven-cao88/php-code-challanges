# PHP Code Challenges

Some PHP Code Challenges

## Challenge 1: User import command
Create a PHP script, that is executed from the command line, which accepts a CSV file as input (see command
line directives below) and processes the CSV file. The parsed file data is to be inserted into a MySQL database.
A CSV file is provided as part of this task that contains test data, the script must be able to process this file
appropriately.


## Installation

Download code or clone from this repo


## Usage
### Import data
```php
php user_upload.php --file users.csv -u <db username> -p <db password> -h <db host>
```

### Just create/rebuild table

```php
php user_upload.php --file users.csv -u <db username> -p <db password> -h <db host> --create_table
```

### Just validate data

```php
php user_upload.php --file users.csv -u <db username> -p <db password> -h <db host> --dry_run
```

### Test
```
./vendor/bin/phpunit tests
```

### Assumptions

- database name is "test" (can be enhanced in future as another directive/option)
- create/rebuilt table will drop existing one (can provide confirmation prompt in future)

## Challenge 2

Create a PHP script that is executed form the command line. The script should:
- Output the numbers from 1 to 100
- Where the number is divisible by three (3) output the word “foo”
- Where the number is divisible by five (5) output the word “bar”
- Where the number is divisible by three (3) and (5) output the word “foobar”

### Usage

```php
php foobar.php
```
