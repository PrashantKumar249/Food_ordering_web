# 🍽️ Khana Khazana - Online Food Ordering Website

Khana Khazana is a dynamic and interactive food ordering platform built using **PHP**, **MySQL**, **AJAX**, and **Tailwind CSS**. It enables users to browse a rich menu, add food items to their cart or wishlist, rate dishes, and place orders with ease. It also features a secure admin panel for managing menu, orders, users, and sales reports.

---

## 📁 Project Structure

```

project/
├── composer.json
├── composer.lock
├── assets/
│   ├── favicon/
│   └── images/
├── user/
│   ├── index.php               # Home page for users
│   ├── menu\_card.php           # Dynamic menu cards
│   ├── add\_cart.php            # Add item to cart (AJAX)
│   ├── cart.php                # User cart
│   ├── wishlist.php            # Wishlist page
│   ├── checkout.php            # Checkout page
│   ├── login.php / register.php
│   ├── profile.php / logout.php
│   ├── my\_orders.php / order\_details.php / order\_invoice.php
│   ├── save\_rating.php         # Handles AJAX rating submissions
│   ├── filter\_items.php / search\_item.php / search\_suggest.php
├── admin/
│   ├── login.php / logout.php
│   ├── index.php               # Admin home
│   ├── dashboard.php           # Order stats
│   ├── manage\_menu.php         # View/Edit/Delete items
│   ├── edit\_menu\_item.php      # Add/Edit form
│   ├── manage\_orders.php       # Manage orders
│   ├── update\_order\_status.php
│   ├── sales\_report.php        # Report by date
│   ├── stock\_alert.php         # Low stock alert
│   ├── user\_orders.php / manage\_users.php
│   ├── order\_details.php / order\_invoice.php
├── include/
│   ├── db.php                  # Database connection
│   ├── header.php / footer.php
│   ├── admin\_header.php / admin\_sidebar.php / admin\_footer.php
├── vendor/                     # Composer dependencies

```

---

## 💡 Key Features

### ✅ User Features
- View menu items with image, price, category, availability, and average rating
- Live **AJAX search** and filter by category
- User login/register system with secure password storage
- Add/remove items to/from **Cart** and **Wishlist** (AJAX-based)
- Rate items (⭐ 5-star system with decimal support, e.g., 4.5)
- View Order History, Detailed Order Page & Download Invoice (PDF)
- Edit profile (address, phone, etc.)
- Secure tokenized URLs to hide internal logic

### 🔐 Authentication
- User and Admin login with role-specific redirects
- Session-based security with input sanitization
- Admin cannot access user area and vice-versa

### 📦 Admin Features
- Dashboard with order summary and statistics
- Manage menu (CRUD operations + availability + stock)
- Manage all orders: view, update status, cancel, delete
- Filter orders by status (Pending, Preparing, Delivered, Cancelled)
- View specific user’s orders
- Sales Report by custom date range
- Stock Alert if quantity is low

---

## 🧰 Tech Stack

- **Frontend**: HTML5, Tailwind CSS, JavaScript (AJAX)
- **Backend**: PHP (Procedural)
- **Database**: MySQL
- **Optional**: phpDocumentor for documentation
- **Security**: Input validation, sanitization, secret keys

---

## ⚙️ Setup Instructions

1. Download or clone the repository in your `htdocs/` folder of XAMPP.
2. Create a new MySQL database (e.g., `khana_khazana`).
3. Import the `database.sql` file if provided.
4. Update DB credentials in `/include/db.php`.
5. Run the project using the following links:
   - User site: [http://localhost/your_folder/user/index.php](http://localhost/your_folder/user/index.php)
   - Admin panel: [http://localhost/your_folder/admin/login.php](http://localhost/your_folder/admin/login.php)

---

## 🌟 Rating System

- ⭐ Users can rate food items from 1 to 5 stars using AJAX
- ⭐ Ratings stored with `user_id`, `menu_item_id`, and numeric value
- ⭐ Average rating is dynamically shown with partially filled stars (e.g., 4.3/5)

---

## 📊 Sales Report

Admin can:
- Filter orders by date range
- View total earnings
- View item-wise order summary and quantity sold
- Export or analyze order trends

---

## 📄 License

This project is built for learning and demonstration purposes. You are free to use, modify, and expand it as needed.

---

## 📷 Flowchart

![Flowchart Overview](A_flowchart_in_the_image_illustrates_the_structure.png)

---

## 📢 Credits

Developed by **Prashant Bhardwaj**  
Technologies: PHP, MySQL, Tailwind CSS, JavaScript  
Database design, UX, and Admin Reporting included.
```

---
