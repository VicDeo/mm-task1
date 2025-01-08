## Description
 - The code was written without any frameworks as per assignment.
 - The only used dependency is `psr/http-message` that defines PSR-7 compatible interfaces.
 - Required PHP version is 8.3.
 - Frontend uses a bit of bootstrap classes.
 - Frontend tables are sortable.  

## Usage
1. Clone project  
`git clone https://github.com/VicDeo/mm-task1.git` 
2. Navigare to the project dir  
`cd mm-task1`
3. Bring up the infrastructure  
`docker compose up -d`
4. Install dependencies  
`docker compose exec app composer install`
5. Browse to http://localhost:8080
6. Follow the wizard
