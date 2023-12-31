
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

Add your Open AI API Token to the following line:
```php
$GLOBALS["open_ai"] = new OpenAi('Your_Token_Here');
```

## Usage
To use this program, you first must connect to your MySQL server, and enter your login credentials. This will allow you to select the Database that you wish to use for these SQL Queries.

![MagicSQL_Login](https://github.com/PotatoLordGrif/magicsql/assets/32713353/81e55e6f-609b-4382-83d2-31c037bd5fac)

On the Left side, you can then enter your Prompt where stated. Once you've entered your prompt for the Database, you hit Run.

![MagicSQL_Prompt](https://github.com/PotatoLordGrif/magicsql/assets/32713353/5baa7193-3ec5-4d1f-98fa-fa907808e8bf)

After a few seconds, you will see SQL query code show on the right side of the menu. Verify that it is correct code, and make any adjustments necessary.
![MagicSQL_Completed](https://github.com/PotatoLordGrif/magicsql/assets/32713353/779f15e6-d9d0-4e70-9c2f-ddce433d1a14)

After making any small adjustments, you can hit Run at the bottom to generate a Table from the SQL.
![MagicSQL_Table](https://github.com/PotatoLordGrif/magicsql/assets/32713353/447b14de-05a3-43fb-975a-052f25e2511b)



