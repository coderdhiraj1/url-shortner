# URL Shortener

A Laravel 12 based multi tenant URL shortener application with role based access control, clients invitations, and short URL management.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- npm
- MySQL
- Laravel 12


## Installation

### 1. Clone the repository

```bash
git clone https://github.com/coderdhiraj1/url-shortner.git
cd url-shortener
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Configure environment variables
Copy the example environment file:

```bash
cp .env.example .env
```

Update the database configuration in .env

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate the application key

```bash
php artisan key:generate
```

### 6. Email SMTP configuration (optional)

SMTP details in .env file is optional this is how application works with or without smtp details.

**Case 1: With SMTP Details**

When Superadmin / Admin tries to invite someone as admin or member an email will be triggered with the invitation link.


**Case 2: Without SMTP Details** (No email will trigger)

Email will not trigger on invitation but along with success messages a *"Click to copy invitation URL"* will appear so that it can be shared with the invitee



### 7. Run Database Migration and seed default superadmin credentials

```bash
php artisan migrate --seed
```


```bash
Default Superadmin Login Credentials

Email: superadmin@example.com
Password: 12345
```


### 8. Build frontend assets

```bash
npm install
npm run build
```


### 9. Build frontend assets

```bash
php artisan serve
```

Congratulation application will be running at http://127.0.0.1:8000