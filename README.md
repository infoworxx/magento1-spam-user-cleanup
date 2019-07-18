# magento1-spam-user-cleanup

Script for cleaning user database in Magento 1 from spam registrations.

## What does it do?

The script selects all:

- unconfirmed user accounts AND
- accounts with missing address AND
- accounts without order(s) AND

- mail address ending with .ru (beware if this is the right use case for you!) OR
- lastname or firstname with invalid patterns

## Customizing

Simply change the ->addAttributeToFilter to your needs.

### Installing

Make a Backup of your data and database !

After that - simply copy the script to your magento root and run from shell with

```
php -f removeInvalidUserAccounts.php
```

to avoid interruption due to the php memory limit use:

```
php -d memory_limit=-1 -f removeInvalidUserAccounts.php
```

# License

GNU GENERAL PUBLIC LICENSE v2.0

WITHOUT ANY WARRANTY!
USE ON YOUR OWN RISK!
MAKE A BACKUP BEFORE USING!

