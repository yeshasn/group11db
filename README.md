# Employee Matchmaker

A web-based application that allows Employees to view and edit their professional information and managers to find suitable employees for their various projects.

## Getting Started

### Dependencies

* You need to have MySQL Server and XAMPP Server downloaded and up and running.

### Creating DB

* Run `CREATE DATABASE <<Insert DB Name>>;`
* Run `USE <<Insert DB Name>>;`
* Run the script `create_tables.sql` to create the tables.
* Run the script `load_data.sql` to load the data into the tables. _(Note: You might need to update your my.cnf)_

### Using the Website

* Place html, css, and php files in a folder under `Applications/XAMPP/htdocs/`.
* Start Apache Web Server in XAMPP manager-osx.
* Type `http://localhost/<<insert name of folder>>/loginpage.html` in web browser.
