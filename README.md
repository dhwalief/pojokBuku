
# 📚 pojokBuku

## Made with

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## System Requirements

Before setting up the application, ensure your system meets the following requirements:

- **Composer**: latest
- **PHP**: 8.3.3
- **Node.js**: 22

In Docker installation you just have docker and run the fck ofgit it

## Installation Guide (1)

Follow these steps to configure and run the application:

### 1. Clone the Repository

```bash
git clone https://github.com/dhwalief/pojokBuku.git
cd pojokBuku
```

### 2. Install Composer Dependencies

Install the necessary PHP dependencies using Composer:

```bash
composer install
```

### 3. Configure the Environment File

Copy the example environment file and configure it according to your environment:

```bash
cp .env.example .env
```

### 4. Install Node.js Dependencies

Install the required frontend dependencies using npm:

```bash
npm install
```

### 5. Generate Application Key

Generate a unique application key:

```bash
php artisan key:generate
```

### 6. Build Frontend Assets

For production:

```bash
npm run build
```

For development:

```bash
npm run dev
```

### Jalankan Program

```bash
php artisan serve
```

## Installation guide (2: Docker)

### 1. Build

```bash
docker compose up -d --build
```

### 2. Configure the Environment File

Copy the example environment file and configure it according to your environment:

```bash
cp .env.example-docker .env
```

### 3. Build image

```bash
docker compose up -d --build
```

### 4. Migrate, Generate Hey, Install Dependecies

```bash
docker exec -it pojokbuku-app php artisan migrate
docker exec -it pojokbuku-app php artisan key:generate
docker exec -it pojokbuku-app npm install
docker exec -it pojokbuku-app npm run build
```
