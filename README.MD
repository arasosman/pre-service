## Pre-service

### structure
- [x] Backend
- [x] Frontend
- [x] Data
  - [x] Redis
  - [x] MariaDB

### for Backend
- [x] Laravel 11
- [x] Docker
- [x] MariaDB
- [x] PHP 8.2
- [x] PHPUnit
- [x] Laravel Pint
- [x] Laravel Sanctum
- [x] Laravel Breeze

### For Frontend
- [x] Vue 3


### 1. Installation
```bash
docker must be installed

git clone https://github.com/arasosman/pre-service.git
cd pre-service
docker-compose up -d
```

### 2. Usage
```bash
docker-compose exec backend php artisan key:generate
docker-compose exec backend php artisan migrate
```

### 3. Testing
```bash
docker-compose exec backend composer test
# inclueded static code analysis and tests
```

### 4. API Documentation
```bash
[POST] http://localhost:8080/api/v1/blogs
[GET] http://localhost:8080/api/v1/blogs
[GET] http://localhost:8080/api/v1/blogs/{id}
[PUT] http://localhost:8080/api/v1/blogs/{id}
[DELETE] http://localhost:8080/api/v1/blogs/{id}

[GET] http://localhost:8080/api/v1/blogs/{id}/comments
[POST] http://localhost:8080/api/v1/blogs/{id}/comments
[GET] http://localhost:8080/api/v1/blogs/{id}/comments/{id}
[PUT] http://localhost:8080/api/v1/blogs/{id}/comments/{id}
[DELETE] http://localhost:8080/api/v1/blogs/{id}/comments/{id}
```

