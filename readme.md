# 🍽️ Food Recommendation API

A RESTful API built with Laravel that provides personalized food recommendations based on user dietary preferences. The system integrates AI to generate explanations and uses asynchronous processing for performance.

---

## 🚀 Features

- Authentication with Laravel Sanctum  
- User dietary profile management  
- Plate and ingredient management  
- AI-powered recommendation explanations (Groq)  
- Asynchronous processing using Queues & Jobs  
- Recommendation history  
- Restaurant management (Admin)  
- Category and menu organization  

---

## 🧠 How It Works

1. User requests analysis:  
`POST /api/recommendations/analyze/{plat_id}`

2. System:
- Creates a recommendation (status: processing)  
- Dispatches a background job  

3. Job:
- Calls AI service (Groq)  
- Calculates compatibility score  
- Generates explanation  
- Updates recommendation  

4. User retrieves result:  
`GET /api/recommendations/{id}`

---

## 🏗️ Architecture

Controller → Job → Service → AIService → Database

---

## 🧰 Tech Stack

- Backend: Laravel (PHP)  
- Database: MySQL  
- Authentication: Laravel Sanctum  
- AI Integration: Groq API  
- Queue System: Laravel Jobs & Workers  

---

## 📦 Installation

git clone https://github.com/yassinesekkah/Brigade-API
cd Brigade-API 

composer install  
cp .env.example .env  
php artisan key:generate  

php artisan migrate  
php artisan serve  

---

## ⚙️ Queue Setup

`php artisan queue:work`

---

## 📄 API Documentation

The API is documented using Swagger (OpenAPI).

Access locally:  
`http://localhost:8000/api/documentation`

---

## 📁 Project Documentation

All project documentation is available in the `docs` folder:

- UML Diagrams  
- Swagger API Documentation  
- Postman Collection  

---

## 🔐 Authentication

`Authorization: Bearer {token}`

---

## 📌 Main Endpoints

### 🔹 Recommendations

- `POST /api/recommendations/analyze/{id}`
- `GET /api/recommendations`
- `GET /api/recommendations/{id}`

### 🔹 Ingredients

- `GET /api/ingredients`
- `POST /api/ingredients`
- `PUT /api/ingredients/{id}`
- `DELETE /api/ingredients/{id}`

### 🔹 Restaurants

- `POST /api/restaurants`
- `GET /api/restaurants/me`

---

## 🧑‍💻 Author

Full Stack Developer (Laravel + React)