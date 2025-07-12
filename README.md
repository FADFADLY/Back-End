# Fadfadly - Back-End

This is the backend of the **Fadfadly** project — an AI-powered mental health support platform.  
It is built using **Laravel 11 (PHP 8.1+)**, providing a secure RESTful API for mobile and web clients, including mood tracking, AI chatbot interaction, quizzes, posts, blogs, books, and real-time notifications.

## 🚀 Tech Stack

- **Framework**: Laravel 11
- **Language**: PHP 8.1+
- **Database**: MySQL
- **Admin Panel**: Filament
- **Authentication**: Laravel Sanctum (API tokens and sessions)
- **AI Integration**: External APIs via Laravel HTTP client
- **Notifications**: Laravel Broadcasting (Reverb/Pusher)
- **Others**: GitHub, Postman, Redis (optional), CI/CD ready

## 📦 Installation

```bash
# 1. Clone the repository
git clone https://github.com/FADFADLY/Back-End.git
cd Back-End

# 2. Install dependencies
composer install

# 3. Copy and configure environment variables
cp .env.example .env
php artisan key:generate

# 4. Set your database and other environment variables in .env

# 5. Run migrations and seeders
php artisan migrate --seed

# 6. (Optional) Link storage for file uploads
php artisan storage:link

# 7. Run the local server
php artisan serve
