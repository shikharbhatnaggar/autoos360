# 🚗 Vehicle Inventory SaaS

A modern **Laravel 12 + MySQL** based Vehicle Inventory & Dealership Management System designed to evolve into a **multi-tenant SaaS platform**.

The application helps used car dealers manage their inventory, purchases, sales, brokers, expenses and business reports from a single dashboard.

---

# Project Vision

Build a scalable SaaS platform where multiple vehicle dealerships can securely manage their own business using a shared application while keeping complete data isolation.

The long-term objective is to provide an affordable cloud-based dealership management solution with modern UI, automation and analytics.

---

# Current Tech Stack

* Laravel 12
* PHP 8.2+
* MySQL
* Blade Templates
* Tailwind CSS (Migration in Progress)
* Alpine.js (Planned)
* Heroicons
* Chart.js (Planned)

---

# Current Features

## Authentication

* Login
* Logout
* Role Based Access
* Branch Access
* Activity Logging

---

## Vehicle Management

* Vehicle Inventory
* Purchase Details
* Selling Details
* Vehicle Status
* Search & Filters

---

## Broker Management

* Add/Edit/Delete Brokers
* Broker Details
* Vehicle Association

---

## Branch Management

* Multiple Branch Support
* Branch-wise Inventory

---

## Finance

* Expense Management
* Profit & Loss Report
* Stock Report

---

## Reports

* Stock Report
* Profit & Loss Report

---

# SaaS Architecture (In Progress)

Current migration towards Multi-Tenant SaaS.

## Implemented

* Subscription Plans
* Tenants
* Tenant Model
* Tenant Middleware
* Tenant Manager
* Tenant Helper
* Tenant Relationship
* Tenant Context Resolution

Example:

```php
tenant()->name;

tenant_id();
```

---

## Planned Tenant Features

* Tenant Isolation
* Global Tenant Scope
* Auto Tenant Assignment
* Dealer Onboarding
* Tenant Settings
* Subscription Management
* Billing
* Feature Management

---

# Project Structure

```
app/

    Http/
        Controllers/
        Middleware/

    Models/

    Policies/

    Services/

    Traits/

    Scopes/

resources/

routes/

database/
```

---

# Architecture

The project follows a Service-Oriented architecture.

```
Controller

        ↓

Service

        ↓

Model

        ↓

Database
```

Business logic is kept inside Services while Controllers remain lightweight.

---

# Planned Folder Structure

```
app/

    Models/

    Services/

    Policies/

    Traits/

    Scopes/

    Observers/

    Repositories/ (Future)

resources/

    views/

        components/

            button.blade.php

            card.blade.php

            table.blade.php

            modal.blade.php

            input.blade.php

            stat-card.blade.php

            page-header.blade.php
```

---

# UI Roadmap

AdminLTE is being replaced with a modern Tailwind CSS interface.

### Planned Design

* Modern Dashboard
* Responsive Layout
* Collapsible Sidebar
* Top Navigation
* KPI Cards
* Charts
* Responsive Tables
* Professional Forms
* Dark Mode
* Notification Center

Design inspiration:

* Linear
* Notion
* Zoho CRM
* HubSpot
* Freshworks

---

# Multi-Tenant Roadmap

## Phase 1

* Tenant Infrastructure
* Subscription Plans
* Authentication
* Tenant Context

Completed

---

## Phase 2

* Tenant Scope
* Tenant Trait
* Branch Isolation
* Broker Isolation
* Vehicle Isolation
* Expense Isolation
* Sales Isolation

In Progress

---

## Phase 3

* Super Admin
* Tenant Management
* Dealer Onboarding
* Subscription Management

Planned

---

## Phase 4

* CRM Module
* Customer Management
* Follow-ups
* Tasks
* Reminders

Planned

---

## Phase 5

* WhatsApp Integration
* Email Notifications
* SMS
* AI Assistant

Future

---

# Future Modules

## Inventory

* Vehicle Images
* Documents
* Insurance
* Service History

---

## Sales

* Booking
* Invoice
* Delivery
* Payment Tracking

---

## Purchase

* Purchase Orders
* Vendor Management

---

## Finance

* Daily Cash Book
* Ledger
* GST Reports

---

## CRM

* Customer Database
* Leads
* Follow-ups
* Test Drive
* Quotation

---

## HR

* Employees
* Attendance
* Payroll

---

## Analytics

* Monthly Sales
* Revenue Trends
* Branch Performance
* Broker Performance
* Inventory Ageing

---

# Coding Standards

* Follow Laravel Naming Conventions
* Business Logic in Services
* Authorization using Policies
* Validation using Form Requests
* Reusable Blade Components
* DRY Principle
* SOLID Principles

---

# Development Workflow

1. Create Migration
2. Create Model
3. Create Policy
4. Create Service
5. Create Request
6. Create Controller
7. Create Blade Views
8. Write Tests

---

# Long-Term Goals

* Production Ready SaaS
* Multi-Tenant Architecture
* REST API
* Mobile App Support
* Dealer Self Registration
* Subscription Billing
* AI Assisted Inventory Management
* Cloud Deployment

---

# Current Status

| Module              | Status |
| ------------------- | ------ |
| Authentication      | ✅      |
| Vehicles            | ✅      |
| Brokers             | ✅      |
| Branches            | ✅      |
| Expenses            | ✅      |
| Sales               | 🚧     |
| Reports             | ✅      |
| SaaS Infrastructure | 🚧     |
| Tailwind UI         | 🚧     |
| CRM                 | 📋     |
| Billing             | 📋     |

---

# License

Private Project

Developed and maintained by **Shiventech Consulting**.

---

# Author

**Shikhar Bhatnagar**

Technology Consultant

Laravel • SaaS • Cloud Solutions • Business Automation

---

**This project is actively evolving from an MVP into a scalable SaaS platform with a strong focus on clean architecture, modern UI, maintainability, and long-term extensibility.**
