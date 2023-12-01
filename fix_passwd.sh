root@cdce1744e7b5:/# mysql -u root -p
Enter password: <from db.env>

MariaDB [(none)]> SET PASSWORD FOR 'borrmant' = PASSWORD('db.env password');

