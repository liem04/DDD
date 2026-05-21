# Exam Engine — MVC vs DDD + Clean Architecture Workshop

## 🎯 Goal

Repository này được xây dựng nhằm mục đích:

* Demo sự khác biệt giữa:

  * Traditional MVC
  * DDD + Clean Architecture

* Training team backend về:

  * Rich Domain Model
  * Entity behavior
  * Value Object
  * Aggregate Root
  * Repository Pattern
  * Domain Event
  * Infrastructure separation

* Minh họa:

  * Vì sao business complexity làm MVC phình logic rất nhanh
  * Vì sao DDD phù hợp với các bài toán enterprise

---

# 📚 Business Problem

Đây là hệ thống Exam Engine.

System hỗ trợ nhiều loại câu hỏi:

* multiple_choice
* true_false
* fill_blank
* essay
* matching

Mỗi loại:

* có cấu trúc answer khác nhau
* có logic grading khác nhau
* có rule business khác nhau

---

# ❌ Traditional MVC Problem

Ban đầu MVC rất ổn:

```php
if ($question->type === 'multiple_choice') {
    ...
}
```

Nhưng khi business phát triển:

* partial score
* matching score
* AI essay scoring
* anti-cheat
* time bonus
* negative marking
* adaptive exam

Logic sẽ bắt đầu:

* mọc if-else everywhere
* service class become God object
* duplicated logic
* khó test
* khó mở rộng

---

# ✅ DDD + Clean Architecture Solution

Repository này implement:

* Rich Domain Model
* Polymorphism
* Aggregate Root
* Value Object
* Domain Event
* Repository abstraction
* Infrastructure isolation

---

# 🏗️ Architecture

```text
src
├── Domain
│   ├── Question
│   ├── Submission
│   ├── Shared
│   └── Score
│
├── Application
│   └── Submission
│
├── Infrastructure
│   ├── Question
│   ├── Submission
│   └── Event
│
└── Presentation
    └── Http
```

---

# 🎯 Layer Responsibility

## Domain

Contains:

* business rules
* entities
* value objects
* aggregate roots
* domain events

Domain DOES NOT know:

* Laravel
* Eloquent
* HTTP
* MySQL

---

## Application

Contains:

* use cases
* orchestration
* transaction boundary

No business logic here.

---

## Infrastructure

Contains:

* Eloquent
* repositories implementation
* event publisher
* persistence mapper

---

## Presentation

Contains:

* controller
* request validation
* API response

---

# 🧠 Important DDD Concepts

---

## 1. Entity ≠ Database Table

Ví dụ:

```text
Question
```

Là business concept.

KHÔNG phải:

* Eloquent model
* database row

---

## 2. Rich Domain Model

Business behavior nằm trong entity:

```php
$question->grade($answer)
```

KHÔNG nằm trong:

* controller
* service
* helper

---

## 3. Polymorphism

Mỗi question type tự implement grading riêng:

```php
MultipleChoiceQuestion
MatchingQuestion
TrueFalseQuestion
```

Thay vì:

```php
if ($type === ...)
```

---

## 4. Value Objects

Examples:

* Score
* QuestionID
* QuestionType
* MatchingPairs

Giúp:

* expressive model
* validation encapsulation
* immutable business concepts

---

## 5. Aggregate Root

```text
Submission
```

Protect business invariant:

* answer uniqueness
* valid question reference
* score consistency

---

## 6. Domain Event

Business event:

```text
SubmissionCreated
```

Được emit từ domain behavior.

KHÔNG emit từ controller.

---

# 🚀 Installation

---

## 1. Clone project

```bash
git clone <repo>
```

---

## 2. Install dependencies

```bash
composer install
```

---

## 3. Setup environment

```bash
cp .env.example .env
```

Update DB config:

```env
DB_DATABASE=exam_engine
DB_USERNAME=root
DB_PASSWORD=
```

---

## 4. Generate app key

```bash
php artisan key:generate
```

---

# 🗄️ Database Setup

---

## Run migration

```bash
php artisan migrate
```

---

## Run seeders

```bash
php artisan db:seed
```

Seeder sẽ tạo:

* sample questions
* sample exams
* sample submissions

---

# 🧪 Run Unit Tests

```bash
php artisan test
```

---

# 📬 API Example

## Submit Exam

### Request

```bash
curl --location 'http://localhost/api/exams/submit' \
--header 'Content-Type: application/json' \
--data '{
    "user_id": 1,
    "exam_id": 1,
    "answers": [
        {
            "question_id": 1,
            "type": "multiple_choice",
            "answer": "B"
        },
        {
            "question_id": 2,
            "type": "true_false",
            "answer": true
        },
        {
            "question_id": 3,
            "type": "fill_blank",
            "answer": "Laravel"
        },
        {
            "question_id": 4,
            "type": "matching",
            "answer": {
                "A": "1",
                "B": "2"
            }
        }
    ]
}'
```

---

# 🧪 Workshop Flow

---

## Part 1 — Traditional MVC

Demo:

* fat controller
* giant service
* if-else explosion
* Eloquent-driven business logic

---

## Part 2 — Problems

Show:

* adding new question type
* modifying scoring rule
* duplicated logic
* testing pain

---

## Part 3 — Refactor to DDD

Introduce:

* Entity
* Value Object
* Aggregate
* Repository
* Domain Event

---

## Part 4 — Final Architecture

Show:

* clean dependency direction
* infrastructure isolation
* polymorphism
* rich domain model

---

# 💥 Key Takeaways

---

## MVC focuses on:

```text
database structure
```

---

## DDD focuses on:

```text
business behavior
```

---

## CRUD complexity ≠ business complexity

DDD becomes valuable when:

```text
business rules grow faster than database schema
```

---

# 🎯 Final Insight

> DDD không giải quyết CRUD complexity
> DDD giải quyết behavior complexity.
