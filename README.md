# Gestion Commerciale

Gestion Commerciale est une application web **Laravel + Filament v4** pour gérer les ventes, les clients, les produits, le stock et les factures.  
Le projet est conçu pour **apprentissage et portfolio**.

---

## 🚀 Tech Stack

- **Backend:** PHP 8.3+, Laravel 10
- **Admin Panel:** Filament v4
- **Frontend:** Blade + Tailwind CSS + Vite
- **Database:** MySQL (ou MariaDB)
- **Local Dev:** Laragon / XAMPP

---

## 📦 Features principales

- Gestion des **clients** (CRUD)
- Gestion des **produits** et catégories
- Gestion des **stocks** avec mouvements automatiques
- Gestion des **factures** (Invoices) et items
- **Dashboard Admin** avec graphiques (ventes, stock faible…)
- Authentification Admin (Filament)
- Génération de données fake pour tests via **seeders & factories**
![Capture d’écran 2025-12-27 185818](https://github.com/user-attachments/assets/9e7685b5-1713-4e12-b3d7-033a5c169cef)


---

## ⚡ Installation Local

1. Clone repository

```bash
git clone https://github.com/lahssiki/Gestion-Commerciale.git
cd Gestion-Commerciale

2 Installer dependencies PHP & JS

composer install
npm install
npm run build


3 Configurer .env

APP_NAME="Gestion Commerciale"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_commerciale
DB_USERNAME=root
DB_PASSWORD=


4 Migrate & Seed

php artisan migrate:fresh --seed


5 Créer un utilisateur Admin Filament

php artisan make:filament-user


6 Lancer le serveur local

php artisan serve


Dashboard Admin → http://127.0.0.1:8000/admin
