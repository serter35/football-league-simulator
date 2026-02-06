# 🏆 Football League Simulator

A high-performance League Management and Simulation tool built with **Laravel 11**, **Vue 3**, and **Inertia.js**. This project demonstrates a strict adherence to **Layered Architecture**, **Atomic Design**, and **Clean Code** principles.

---

## 🏗 Architectural Overview

### Backend (Laravel)
The backend is designed to be decoupled and highly testable, following the **Service-Repository Pattern**.
* **Plain Controllers:** Controllers are kept thin, acting only as entry points to call the relevant Services.
* **Service Layer:** All business logic, including fixture generation and point calculations, resides in specialized Service classes.
* **Repository Pattern:** Database interactions are abstracted via Interfaces, ensuring flexibility and easier unit testing.
* **Value Objects:** Custom Value Objects are used to ensure data integrity across layers.
* **Bulk Operations** Implementations like `fillAndInsert` are utilized for optimized bulk database operations.

### Frontend (Vue 3 & Inertia.js)
The frontend utilizes a modern, reactive architecture to provide a seamless user experience.
* **Atomic Design:** Components are strictly organized into `Atoms`, `Molecules`, and `Organisms` to prevent oversized, monolithic components.
* **Context API:** Global state and league data are managed via `LeagueContext` to eliminate "Props Drilling."
* **Composables:** Reusable logic (e.g., `useLeagueActions`) is extracted into composables for cleaner SFCs.
* **Inertia Optimization:** Leveraging `Partial Reloads` and `Defer` to ensure fast navigation and data synchronization without full page refreshes.
* **Client-Side Standings:** Real-time table updates are calculated on the fly using a dedicated `LeagueCalculator` utility, providing instant feedback as scores are modified.

---

## 🛠 Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Framework** | Laravel 11 |
| **Frontend** | Vue 3 (Composition API) |
| **Bridge** | Inertia.js |
| **Styling** | Tailwind CSS |
| **Testing** | Pest / Mockery |
| **State** | Context API (Provide/Inject) |

---

## 🚀 Key Features

* **Dynamic Fixture Generation:** 4-team double round-robin algorithm with automated shuffling.
* **Live Standings:** Real-time reactivity where the league table updates instantly upon score entry.
* **Simulation Engine:** Single-week and full-season simulation capabilities via the Service layer.
* **Validation:** Robust server-side validation using `FormRequests`.

---

## 📂 Project Structure

```text
app/
├── Contracts/        # Interface Definitions (Repositories/Services)
├── Repositories/     # Data Access Layer (Eloquent Implementation)
├── Services/         # Business Logic Layer
├── Http/
│   ├── Controllers/  # Plain Controllers
│   ├── Requests/     # Validation Logic (FormRequest)
│   └── Resources/    # Data Transformation (Resources/DTOs)
resources/js/
├── components/       # Atomic Design Implementation (Atoms, Molecules, etc.)
├── contexts/         # State Management (Provide/Inject)
├── composables/      # Shared/Reusable Logic
└── utils/            # Pure JS Logic (Calculators & Formatters)
```

## 🔧 Installation

Follow these steps to get the project up and running:

1. **Clone the repository:**
```bash
git clone https://github.com/serter35/football-league-simulator.git
cd football-league-simulator
```

2. **Setup Backend:**
```bash
composer install
cp .env.example .env
php artisan key:generate
# Configure your database in .env then:
php artisan migrate --seed
```

3. **Setup Frontend:**
```bash
npm install
npm run dev
```

4. **Run Tests:**
```bash
php artisan test
```

## 🔒 Security
- **Authentication:** Basic Auth is required for all routes.
- **Rate Limiting:** Throttle middleware is active (60 requests/minute) to prevent brute-force and resource exhaustion.

## 🧪 Testing Policy
This project maintains high test coverage for the Service Layer using Pest and Mockery. Every core logic component, 
from fixture shuffling to score persistence, is unit tested to ensure reliability and prevent regressions in the 
business logic.

```bash
# Run specific service tests
php artisan test --filter FixtureServiceTest
```

## 📝 License
This project is open-sourced under the MIT license.
