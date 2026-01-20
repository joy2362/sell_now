# SellNow – Engineering Manifesto

## Overview

SellNow is a refactoring exercise of an inherited PHP 8.x digital marketplace prototype.
The original codebase was functional but architecturally fragile—logic-heavy controllers, weak boundaries, and implicit assumptions about data, security, and scalability.

This refactor focuses **not on feature completeness**, but on **engineering maturity**:

* Clear responsibility boundaries
* Predictable data flow
* Replaceable components
* Explicit security posture
* Sustainable growth without framework crutches

No full-stack framework (Laravel, Symfony, etc.) was used—by design.

---

## 1. The Audit (Inherited State)

### What Worked

* Basic marketplace flows existed (auth, products, cart, checkout)
* SQLite-backed persistence was functional
* Twig templates provided a clean separation from HTML
* Composer was already present (a crucial foothold)

### Key Problems Identified

#### 1. Controllers as “God Objects”

Controllers:

* Contained request parsing
* Performed validation
* Executed business logic
* Talked directly to persistence
* Made security decisions inline

This made the system:

* Hard to reason about
* Difficult to test
* Brittle to change

#### 2. Implicit Data Contracts

* Request data was trusted too early
* Validation was inconsistent or missing
* Domain rules lived in controllers instead of domain logic

There was no clear answer to:

> “At what point is data considered valid?”

#### 3. Tight Coupling Everywhere

* Database access was concrete and globally assumed
* No interfaces for infrastructure concerns
* Swapping MySQL/SQLite, payments, or notifications would require invasive edits

#### 4. Security by Accident

* No centralized input sanitization
* No explicit CSRF strategy
* Authentication logic mixed with request handling
* File uploads trusted client-provided values

Security relied on “nothing bad happens” rather than design.

#### 5. No Scalable Mental Model

Adding a new feature would mean:

* Copy-pasting controller logic
* Introducing more conditionals
* Increasing cross-file knowledge requirements

The code worked—but did not *scale cognitively*.

---

## 2. Refactoring Philosophy

This refactor follows **evolution, not erasure**.

The goal was to **introduce structure gradually**, respecting existing behavior while reshaping how the system thinks.

Key principles:

* **Explicit over implicit**
* **Composition over inheritance**
* **Contracts over concretion**
* **Boundaries before features**

---

## 3. The New Architecture (Observed Changes)

### High-Level Structure

```
sell_now/
├── public/           → HTTP entry point
├── routes/           → Routes 
├── src/
│   ├── Controllers/  → Thin request orchestration
│   ├── Services/     → Business logic
│   ├── Abstract/     → Abstract classes
│   ├── Domain/       → Core business rules & entities
│   ├── Middleware/   → Middleware for each request 
│   ├── Helpers/      → Reuseable functions
│   ├── Drivers/      → Infrastructure (DB, external IO)
│   ├── Interface/    → Interfaces for replaceable components
├── config/           → Environment & app configuration
├── templates/        → Twig views
├── vendor/           → Composer-managed dependencies
└── .env              → Environment-specific configuration
```

---

## 4. Responsibility of a Component

### Controllers

* Parse HTTP requests
* Delegate work to Services
* Return responses

Controllers **do not**:

* Contain business rules
* Validate domain constraints
* Access the database directly

### Services

* Own business workflows (checkout, payments, cart rules)
* Coordinate multiple repositories or components
* Enforce use-case rules

### Domain

* Contains entities and value objects
* Protects invariants
* Knows nothing about HTTP or databases

### Repositories

* Translate domain objects ↔ persistence
* Hide SQL and storage details
* Are accessed via interfaces

---

## 5. The Contract of Data

Data moves through **explicit stages**:

1. **Raw HTTP Input**
2. **Request DTO / Validator**
3. **Domain-safe objects**
4. **Persistence**

Invalid data is rejected **before** it reaches the domain layer.

This ensures:

* Predictable behavior
* Fewer runtime surprises
* Easier testing of business logic

---

## 6. Interface of Change

Key volatile components are abstracted behind interfaces:

* Database drivers
* Payment providers
* Environment configuration
* Authentication mechanisms

Example philosophy:

> “If this service disappears tomorrow, how painful is the replacement?”

The answer should be: *localized pain, not systemic collapse*.

---

## 7. Ethics of Security

Without a framework safety net, security is **explicitly owned**:

* Password hashing centralized
* Authentication logic isolated
* No raw superglobals in business logic
* File uploads treated as hostile
* Environment secrets moved to `.env`
* Database access parameterized

Security is not “added later”—it is **structural**.

---

## 8. Pragmatic Performance

The refactor avoids premature optimization but addresses obvious risks:

* Centralized DB access prevents N+1 query sprawl
* Reduced redundant IO
* Clear data boundaries prevent unnecessary transformations

Performance philosophy:

> “Make the slow things obvious before making them fast.”

---

## 9. Enforcement of Best Practices

Without a framework enforcing discipline, the codebase enforces it itself:

* Composer autoloading
* Namespaced classes
* Dependency Injection
* Interfaces for infrastructure
* Consistent directory semantics
* PHP 8.x type hints

Clean Code here means:

* Predictable file locations
* Boring, readable functions
* Minimal hidden state

---

## 10. Priority Matrix (What Was Chosen—and Why)

### High Priority

* Architectural boundaries
* Data validation flow
* Dependency inversion
* Security posture
* Directory clarity

### Deprioritized (Intentionally)

* UI polish
* Feature expansion
* Micro-optimizations
* Over-engineering edge cases

This was a **signal exercise**, not a production launch.

---

## 11. Trade-offs

Every decision has cost.

### What Was Sacrificed

* Speed of feature addition
* Initial verbosity
* Short-term simplicity

### What Was Gained

* Long-term maintainability
* Testability
* Change tolerance
* Clear mental model

---

## 12. Final Reflection

This refactor demonstrates how to **respect reality**:

* Messy starting points
* Incomplete requirements
* Time constraints
* Imperfect legacy decisions

The goal was not perfection—but **professional intent**.

If a new engineer joins this project, they should understand:

* Where logic lives
* How data flows
* How change happens safely

That is the true deliverable.

---

## 13. Setup & Local Development

This project intentionally avoids framework-specific tooling.
All setup steps are explicit and environment-agnostic.

### Prerequisites

* PHP 8.x
* Composer
* SQLite **or** MySQL
* Git

---

### 1. Environment Configuration

Copy the example environment file:

```
cp .env.example .env
```

Configure the database connection in `.env`:

```
DATABASE_CONNECTION=sqlite
```

or

```
DATABASE_CONNECTION=mysql
```

Additional database credentials (for MySQL) should be defined explicitly in the same file.

---

### 2. Install Dependencies

Install Composer dependencies:

```
composer install
```

---

### 3. Database Migrations

Run existing database migrations:

```
vendor/bin/phinx migrate
```

This will initialize the database schema based on the configured connection.

---

### 4. Creating New Migrations

To create a new migration:

```
vendor/bin/phinx create MyNewMigration
```

Migration files are versioned explicitly and act as the **single source of truth** for schema evolution.

---

### 5. Static Code Analysis

Run static analysis using PHPStan:

```
vendor/bin/phpstan analyse --level 5 src
```

PHPStan is used to enforce:

* Type safety
* Explicit contracts
* Early detection of architectural drift

The goal is **signal over silence**, not perfect scores.

---

**Author’s Note:**
This project reflects how I approach inherited systems in the real world:
incrementally, deliberately, and with empathy for both users *and* future developers.

---
