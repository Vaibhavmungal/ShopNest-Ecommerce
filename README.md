# 🛍️ ShopNest — PHP E-Commerce Platform

> A full-featured, modern e-commerce web application built with PHP, MySQL, and Bootstrap 5. Includes a complete customer-facing storefront and a powerful admin panel.

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

## 🚀 Getting Started

### Prerequisites
- PHP 8.0+
- MySQL 8.0+
- Apache (XAMPP recommended)

### Installation

**1. Clone the repository**
```bash
git clone https://github.com/Vaibhavmungal/ShopNest-Ecommerce.git
cd ShopNest-Ecommerce
```

**2. Move to your web server root**
```
Place the folder in: xampp/htdocs/aws-ecommerce
```

**3. Setup the database**
```bash
# Import the SQL schema
mysql -u root -p < database/schema.sql
```

**4. Configure environment**
```bash
cp .env.example .env
```

Edit `.env` with your settings:
```env
DB_HOST=localhost
DB_USER=root
DB_PASS=your_password
DB_NAME=aws_ecommerce

APP_NAME=ShopNest
APP_URL=http://localhost/aws-ecommerce
APP_ENV=development
APP_DEBUG=true

**5. Open in browser**
```
http://localhost/aws-ecommerce
```

---

## 📁 Project Structure

```
aws-ecommerce/
├── admin/              # Admin panel pages
│   ├── categories/
│   ├── customers/
│   ├── inventory/
│   ├── orders/
│   ├── products/
│   ├── reports/
│   ├── includes/
│   ├── index.php       # Admin dashboard
│   └── login.php
├── api/                # AJAX endpoints (cart, wishlist, search)
├── assets/
│   ├── css/            # Stylesheets
│   ├── js/             # main.js — cart, wishlist, search, filters
│   └── images/
├── config/
│   ├── database.php    # PDO connection
│   ├── constants.php   # App constants
│   └── aws.php         # AWS SDK config
├── customer/           # Customer pages (cart, checkout, profile)
├── database/           # SQL schema & seeds
├── includes/           # Shared PHP (header, footer, functions)
├── products/           # Product listing & detail pages
├── uploads/            # Uploaded product images
├── .env                # Environment config (not committed)
├── .env.example        # Environment template
└── index.php           # Homepage
```

---

## 🔐 Admin Access

Default admin credentials (change after first login):
```
URL:      http://localhost/aws-ecommerce/admin/login.php
Email:    admin@shopnest.com
Password: (set in DB during setup)
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8+ (procedural + PDO) |
| Database | MySQL 8 |
| Frontend | HTML5, Bootstrap 5.3, Vanilla JS |
| Icons | Bootstrap Icons |
| Fonts | Google Fonts — Outfit, Inter |
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

---

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👤 Author

**Vaibhav Mungal**

- GitHub: [@Vaibhavmungal](https://github.com/Vaibhavmungal)

---

<div align="center">
  Made with ❤️ using PHP & Bootstrap
</div>
