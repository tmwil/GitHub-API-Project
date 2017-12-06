# GitHub-API-Project
A small PHP/MySQL project that retrieves and stores the top starred PHP repositories from git.

### Assignment
> #### Popular PHP Repositories on GitHub
>  1. Use the GitHub API to retrieve the most starred public PHP projects. Store the list of repositories in a MySQL table. The table must contain the repository ID, name, URL, created date, last push date, description, and number of stars. This process should be able to be run repeatedly and update the table each time.
> 
>  2. Using the data in the table created in step 1, create an interface that displays a list of the GitHub repositories and allows the user to click through to view details on each one. Be sure to include all of the fields in step 1 – displayed in either the list or detailed view.
> 
>  3. Create a README file with a description of your architecture and notes on installation of your application. You are free to use any PHP, JavaScript, or CSS frameworks as you see fit.

## Architecture & Instillation

##### Languages/Services Used:

    PHP
    MySQL
    CSS
    JavaScript
    Google Fonts
    ajaxload.info - to generate loading gif
    markdownlivepreview.com - help writing this README

#### Required Environment:

* HTTP web server with:

    * `PHP` 5.6 or newer with `cURL` enabled (would likely work on older versions oh PHP as well, but was not tested)
    * `MySQL`
    * `.htaccess` support

#### Setup
1. Clone the GitHub-API-Project repository and copy files to HTTP server.

    * `git clone https://github.com/tmwil/GitHub-API-Project.git`

2. Setup MySQL database for project and create user with `ALL PRIVILEGES`

3. Create following table (or import `/sql/git_repos.sql` from repo):

    * `CREATE TABLE 'git_repos' (`
    * `'id' int(11) NOT NULL,`
    * `'name' varchar(255) NOT NULL DEFAULT '',`
    * `'url' varchar(255) NOT NULL DEFAULT '',`
    * `'date_created' datetime NOT NULL,`
    * `'date_last_push' datetime NOT NULL,`
    * `'description' text NOT NULL,`
    * `'stars' int(11) NOT NULL`
    * `) ENGINE=InnoDB DEFAULT CHARSET=utf8;`

4. Add Keys/Indexes to table (If you chose not to import supplied .sql file)

    * `ALTER TABLE 'git_repos'`
    * `ADD PRIMARY KEY ('id'),`
    * `ADD KEY 'stars' ('stars');`
    * `COMMIT;`

5. Modify `/src/Database.php` lines 6 - 9 to add database credentials

    * `private $db_host = 'YOUR_DB_HOST';'`
    * `private $db_name = 'YOUR_DB_NAME';'`
    * `private $db_user = 'YOUR_DB_USER';'`
    * `private $db_pass = 'YOUR_DB_PASSWORD';'`

6. Modify `/src/GitData.php` lines 17 - 18 to add GitHub credentials

    * `private $git_user = 'YOUR_GIT_USER';`
    * `private $git_pass = 'YOUR_GIT_PASSWORD';`

6. Navigate to HTTP server in browser and click `Update Repositories` to get started!