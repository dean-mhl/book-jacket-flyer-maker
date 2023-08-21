# Book Jacket Flyer Maker
Online tool for quickly generating flyers containing a 5 x 4 grid of book covers from the MVLC catalog plus an optional header. Covers can be retrieved from a list of ISBNs or a catalog search, including one of a couple "canned" searches MVLC created for MHL (New Books and New Teen Room titles).

Requires an Apache webserver with PHP and mod_rewrite enabled as well as a MySQL server.
## Setup ##
1. Create a MySQL database. Grant a database user all privileges on that database.
2. Edit config.php to include the name and password of the database user, the name of the database, and the host name MySQL is running on (e.g., 'localhost').
3. Import the db_tables.sql file to create the necessary database tables. E.g., `mysql -u [user] -p [database_name] < db_tables.sql`.
