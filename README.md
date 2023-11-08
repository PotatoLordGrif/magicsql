
# MagicSQL

MagicSQL is a SQL code generator using OpenAI's ChatGPT. The code is based in php using HTML and CSS.



## Requirements
* [Orhanerday's Open-AI Connector](https://github.com/orhanerday/open-ai)
* PHP Version 7.4+
* Webserver that supports PHP
* OpenAI API Token
* MySQL Server
## Installation
Clone this repository to your webserver
```bash
git clone https://github.com/potatolordgrif/magicsql.git
```
Install Orhanerday's Open-AI Connector
```bash
composer require orhanerday/open-ai
```
For more information go to [Orhanerday's Repository](https://github.com/orhanerday/open-ai)

## Usage
To use this program, you first must connect to your MySQL server, and enter your login credentials. This will allow you to select the Database that you wish to use for these SQL Queries.

On the Left side, you can then enter your Prompt where stated. Once you've entered your prompt for the Database, you hit Run.

After a few seconds, you will see SQL query code show on the right side of the menu. Verify that it is correct code, and make any adjustments necessary, and then you are able to hit Run. This will display the SQL data in a table at the bottom of the page.

