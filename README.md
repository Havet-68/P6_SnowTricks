# P6_SnowTricks

This project is a blog made with Symfony. Its goals were to learn basics of this framework.


## Installation


Install the project by cloning it onto your system using git

```
  git clone https://github.com/Havet57/P6_SnowTricks P6_SnowTricks
  cd P6_SnowTricks
  composer install
```

## Database

Please create a mysql database named `p6_tricks` with utf8_general_ci. 
Then run this command line `mysql -uroot -p p6_tricks < database.sql` to create all the tables.

## Environment Variables

To run this project, you must update the `config/database.json` with your database values (host, user, password, dbname).
 
