# 🛍️ ShopNest — PHP E-Commerce Platform

> A full-featured, modern e-commerce web application built with PHP 8.2, MySQL 8, and Bootstrap 5.3.
> Includes a complete customer-facing storefront, a powerful admin panel, Docker support, and a
> GitHub Actions CI/CD pipeline using Maven.

[![CI/CD Pipeline](https://github.com/Vaibhavmungal/ShopNest-Ecommerce/actions/workflows/ci-cd.yml/badge.svg)](https://github.com/Vaibhavmungal/ShopNest-Ecommerce/actions/workflows/ci-cd.yml)
[![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?logo=mysql)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker)](https://docs.docker.com/compose/)
[![Maven](https://img.shields.io/badge/Maven-3.x-C71A36?logo=apache-maven)](https://maven.apache.org/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack & Versions](#️-tech-stack--versions)
- [Project Structure](#-project-structure)
- [Getting Started](#-getting-started)
  - [Option A — Local (XAMPP)](#option-a--local-xampp)
  - [Option B — Docker Compose](#option-b--docker-compose)
- [Environment Variables](#-environment-variables)
- [CI/CD Pipeline](#-cicd-pipeline)
  - [How the Pipeline Works](#how-the-pipeline-works)
  - [Setting Up the Workflow](#setting-up-the-workflow)
  - [Maven POM & Build System](#maven-pom--build-system)
  - [Running Maven Locally](#running-maven-locally)
- [Admin Access](#-admin-access)
- [Key Pages](#-key-pages)
- [Contributing](#-contributing)
- [License](#-license)
- [Author](#-author)

---

## ✨ Features

### 🛒 Customer Side
- **Homepage** — Hero section, featured products, categories, new arrivals, promo banner & testimonials
- **Product Listing** — Filter by category, price range, featured & sale; sort by newest / price / rating / A–Z
- **Product Detail** — Image gallery, star ratings, reviews, stock status, quantity stepper
- **Shopping Cart** — Add/remove items, quantity update, tax & shipping calculation
- **Wishlist** — Save products for later
- **Checkout** — Address form, payment method selection
- **User Auth** — Register, Login, Profile management
- **Search** — Live autocomplete search with suggestions

### 🔧 Admin Panel
- **Dashboard** — Revenue, orders, customers & product stats with animated counters
- **Products** — Add, edit, delete products with image upload
- **Categories** — Manage product categories with slugs and sort order
- **Orders** — View and update order statuses
- **Customers** — Customer list with order history
- **Inventory** — Stock management and low-stock alerts
- **Reports** — Sales reports and analytics

---

## 🛠️ Tech Stack & Versions

| Layer | Technology | Version |
|-------|-----------|---------|
| **Backend** | PHP (procedural + PDO) | **8.2** |
| **Database** | MySQL | **8.0** |
| **Web Server** | Apache (mod_rewrite enabled) | **2.4** |
| **Frontend** | HTML5 + Vanilla JS | — |
| **CSS Framework** | Bootstrap | **5.3** |
| **Icons** | Bootstrap Icons | **1.x** |
| **Fonts** | Google Fonts (Outfit, Inter) | — |
| **Containerization** | Docker + Docker Compose | **v2+** |
| **Build / CI Orchestrator** | Apache Maven | **3.x** |
| **Java Runtime (for Maven)** | Eclipse Temurin JDK | **17 (LTS)** |
| **CI/CD Platform** | GitHub Actions | — |

### Maven Plugin Versions (pom.xml)

| Plugin | Version |
|--------|---------|
| `maven-clean-plugin` | **3.3.2** |
| `exec-maven-plugin` | **3.1.1** |
| `maven-assembly-plugin` | **3.6.0** |

---

## 📁 Project Structure

```
aws-ecommerce/
├── .github/
│   └── workflows/
│       └── ci-cd.yml        # GitHub Actions CI/CD pipeline
├── admin/                   # Admin panel pages
│   ├── categories/
│   ├── customers/
│   ├── inventory/
│   ├── orders/
│   ├── products/
│   ├── reports/
│   ├── includes/
│   ├── index.php            # Admin dashboard
│   └── login.php
├── api/                     # AJAX endpoints (cart, wishlist, search)
├── assets/
│   ├── css/                 # Stylesheets
│   ├── js/                  # main.js — cart, wishlist, search, filters
│   └── images/
├── config/
│   ├── database.php         # PDO connection
│   └── constants.php        # App constants
├── customer/                # Customer pages (cart, checkout, profile)
├── database/                # SQL schema & seeds
├── docker/
│   └── php.ini              # Custom PHP config (limits, timezone, opcache)
├── includes/                # Shared PHP (header, footer, functions)
├── products/                # Product listing & detail pages
├── tools/                   # CLI utilities (setup, hash generator)
├── uploads/                 # Uploaded product images (gitignored)
├── logs/                    # PHP error logs (gitignored)
├── assembly.xml             # Maven assembly descriptor (packaging rules)
├── pom.xml                  # Maven POM (lint + package CI/CD)
├── Dockerfile               # Docker image definition (PHP 8.2 + Apache)
├── docker-compose.yml       # Multi-container setup (app + db + phpmyadmin)
├── .env                     # Environment config (NOT committed)
├── .env.example             # Environment template
└── index.php                # Homepage
```

---

## 🚀 Getting Started

### Option A — Local (XAMPP)

**Prerequisites:**
- [XAMPP](https://www.apachefriends.org/) with **PHP 8.2+** and **MySQL 8.0+**

**1. Clone the repository**
```bash
git clone https://github.com/Vaibhavmungal/ShopNest-Ecommerce.git
```

**2. Move to the XAMPP web root**
```
Place the cloned folder at: xampp/htdocs/aws-ecommerce
```

**3. Import the database schema**
```bash
mysql -u root -p < database/ecommerce.sql
```

**4. Configure your environment**
```bash
cp .env.example .env
```
Edit `.env` with your local settings (see [Environment Variables](#-environment-variables) below).

**5. Open in browser**
```
http://localhost/aws-ecommerce
```

---

### Option B — Docker Compose

**Prerequisites:**
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Docker Engine **v24+** / Compose **v2+**)

**1. Clone the repository**
```bash
git clone https://github.com/Vaibhavmungal/ShopNest-Ecommerce.git
cd ShopNest-Ecommerce
```

**2. Configure your environment**
```bash
cp .env.example .env
# Edit .env as needed (DB credentials, APP_URL, etc.)
```

**3. Start all services**
```bash
docker compose up -d --build
```

This starts **3 containers**:

| Container | Image | Port |
|-----------|-------|------|
| `shopnest_app` | Custom (PHP 8.2 + Apache) | `http://localhost:8080` |
| `shopnest_db` | `mysql:8.0` | `localhost:3307` |
| `shopnest_pma` | `phpmyadmin:latest` | `http://localhost:8081` |

**4. View logs**
```bash
docker compose logs -f app
```

**5. Stop all services**
```bash
docker compose down
```

**6. Stop and remove all data (full reset)**
```bash
docker compose down -v
```

---

## 🔐 Environment Variables

Copy `.env.example` to `.env` and fill in your values:

```env
# --- Database ---
DB_HOST=localhost          # Use "db" when running with Docker Compose
DB_PORT=3306
DB_USER=shopnest
DB_PASS=shopnest_pass
DB_NAME=aws_ecommerce

# --- Application ---
APP_NAME=ShopNest
APP_URL=http://localhost/aws-ecommerce   # http://localhost:8080 for Docker
APP_ENV=development        # development | production
APP_DEBUG=true             # false in production

# --- AWS (optional, for S3 image uploads) ---
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
```

> **⚠️ Never commit your `.env` file.** It is already listed in `.gitignore`.

---

## ⚙️ CI/CD Pipeline

This project uses **GitHub Actions** as the CI/CD platform and **Apache Maven** as the build orchestrator. The pipeline runs automatically on every `push` or `pull_request` to the `main` / `master` branch.

### How the Pipeline Works

```
Push / PR → GitHub Actions Runner (Ubuntu Latest)
               │
               ├── 1. Checkout code        (actions/checkout@v4)
               ├── 2. Setup PHP 8.2        (shivammathur/setup-php@v2)
               │        extensions: pdo, pdo_mysql, gd, zip, mysqli
               ├── 3. Setup JDK 17         (actions/setup-java@v4, Temurin)
               │        (required to run Maven)
               ├── 4. mvn clean package
               │        ├── clean   → removes old target/ directory
               │        ├── test    → runs PHP lint check (php -l) on all .php files
               │        └── package → creates shopnest-ecommerce-1.0.0-SNAPSHOT.zip
               │                      and shopnest-ecommerce-1.0.0-SNAPSHOT.tar.gz
               └── 5. Upload artifacts     (actions/upload-artifact@v4)
                        retention: 7 days
```

### Setting Up the Workflow

The workflow file is already included at `.github/workflows/ci-cd.yml`. Follow these steps to activate it:

**Step 1 — Push to GitHub**

Make sure your repository is on GitHub and the workflow file is committed:
```bash
git add .github/workflows/ci-cd.yml pom.xml assembly.xml
git commit -m "chore: add CI/CD pipeline"
git push origin main
```

**Step 2 — Verify the Action runs**

1. Go to your repository on GitHub.
2. Click the **Actions** tab.
3. You should see **"ShopNest CI/CD Pipeline"** listed.
4. Click it to see the run details and logs for each step.

**Step 3 — Download build artifacts**

After a successful run:
1. Open the workflow run page on GitHub Actions.
2. Scroll to the **Artifacts** section at the bottom.
3. Download `shopnest-deployment-packages` — it contains your `.zip` and `.tar.gz` deployment bundles.

**Step 4 — (Optional) Add Secrets for deployment**

If you want to auto-deploy after a successful build, add secrets to your repository:

1. Go to **Settings → Secrets and variables → Actions → New repository secret**.
2. Add secrets such as:

| Secret Name | Description |
|-------------|-------------|
| `SSH_HOST` | Your server IP / hostname |
| `SSH_USER` | SSH username |
| `SSH_PRIVATE_KEY` | Private key for SSH access |
| `AWS_ACCESS_KEY_ID` | AWS key (if using S3) |
| `AWS_SECRET_ACCESS_KEY` | AWS secret (if using S3) |

Then extend `.github/workflows/ci-cd.yml` with a `deploy` job that runs after the `build-and-package` job passes.

---

### Maven POM & Build System

The [`pom.xml`](pom.xml) file uses Maven purely as a **CI/CD orchestrator** — it does not manage PHP dependencies (PHP doesn't use Maven for that). Here is what each plugin does:

| Maven Plugin | Phase | Purpose |
|---|---|---|
| `maven-clean-plugin` **3.3.2** | `clean` | Deletes the `target/` directory before every build |
| `exec-maven-plugin` **3.1.1** | `test` | Runs `php -l` on every `.php` file to catch syntax errors |
| `maven-assembly-plugin` **3.6.0** | `package` | Bundles app files into `.zip` & `.tar.gz` deployment archives |

The [`assembly.xml`](assembly.xml) descriptor controls which files go into the archive — it **excludes**:
`.git/`, `target/`, `uploads/`, `logs/`, `.env`, `.env.example`, `pom.xml`, `assembly.xml`

**SonarQube (Static Analysis) — Optional**

The `pom.xml` includes SonarQube properties pre-configured for PHP. To run a SonarQube scan:
```bash
mvn sonar:sonar \
  -Dsonar.host.url=http://your-sonarqube-server \
  -Dsonar.login=your-token
```

---

### Running Maven Locally

**Prerequisites:**
- [Apache Maven 3.x](https://maven.apache.org/download.cgi) installed
- Java 17 (JDK) installed — [Eclipse Temurin](https://adoptium.net/)
- PHP 8.2 available in your `PATH`

**Verify your setup:**
```bash
mvn --version   # Should show Apache Maven 3.x
java --version  # Should show openjdk 17
php --version   # Should show PHP 8.2.x
```

**Common Maven commands:**

```bash
# Clean the target/ directory
mvn clean

# Run PHP syntax lint check only
mvn test

# Full build: clean + lint + package into zip/tar.gz
mvn clean package

# Skip linting and just package
mvn clean package -DskipTests

# View what will be packaged (dry run)
mvn clean package -Dassembly.dryRun=true
```

After `mvn clean package`, your deployment artifacts appear in:
```
target/
├── shopnest-ecommerce-1.0.0-SNAPSHOT.zip
└── shopnest-ecommerce-1.0.0-SNAPSHOT.tar.gz
```

---

## 🔐 Admin Access

Default admin credentials (change after first login):

```
URL:      http://localhost/aws-ecommerce/admin/login.php
          http://localhost:8080/admin/login.php   (Docker)
Email:    admin@shopnest.com
Password: (set in DB during setup)
```

---

## 📦 Key Pages

| Route | Description |
|-------|-------------|
| `/` | Homepage |
| `/products/index.php` | Product listing with filters |
| `/products/detail.php?slug=...` | Product detail |
| `/customer/cart.php` | Shopping cart |
| `/customer/checkout.php` | Checkout |
| `/customer/wishlist.php` | Wishlist |
| `/admin/` | Admin dashboard |
| `/admin/products/` | Product management |
| `/admin/orders/` | Order management |
| `/admin/login.php` | Admin login |

---

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

The CI/CD pipeline will automatically lint and validate your code when you open a PR.

---

## 📄 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

## 👤 Author

**Vaibhav Mungal**

- GitHub: [@Vaibhavmungal](https://github.com/Vaibhavmungal)

---

<div align="center">
  Made with ❤️ using PHP, Bootstrap &amp; Docker
</div>
