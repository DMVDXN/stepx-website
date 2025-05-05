StepX


#Project name and description

  *StepX is a modern eCommerce website built to sell sneakers online. It includes user authentication, dynamic
  product listings, a shopping cart system, wishlist/favorites, and review functionality — all styled with a clean, 
  responsive UI and light/dark mode support. The project was built using HTML, CSS, PHP, MySQL, and 
  JavaScript, and is locally hosted using MAMP.


#List of features implemented 

  *User Authentication

    -Sign up and login functionality with hashed passwords
    -Session-based login persistence 
    -Editable user profile page
  
  *Product Catalog

    -Dynamic loading of sneaker data from MySQL database
    -Responsive product grid with images, names, and prices
    -Individual product detail pages

  *Favorites System

    -Heart icon toggles favorite status via AJAX
    -Favorites stored in database per user
  
  *Shopping Cart

    -Add-to-cart functionality (database-driven)
    -Cart page with quantity controls (+ / –)
    -Linked to user session
  
  *Reviews

    -Submit reviews with star rating and text
    -View submitted reviews under Notifications
    -Delete submitted reviews
  
  *Notifications Page

    -View all submitted reviews (future expansion planned)
  
  *Dark Mode & Light Mode

    -Toggle across all pages using button-based switch
    -Consistent styling for both modes
  
  *Unified Header

    -Consistent navigation bar across all pages
    -Includes logo, search bar, cart, favorites, bell (notifications), and user icon


#List of features not implemented

  *Notifications system (alerts, messages)
  *Checkout / payment gateway integration
  *Product filtering (by brand, color, price)
  *Search functionality (non-functional input field exists)
  *Admin panel for managing products
  *Profile image uploads
  *Favorite heart animation on favorites page


#Any known bugs or limitations

  *No front-end validation on login/signup forms
  *Star rating display does not support half-stars
  *No pagination for product listing or reviews
  *Error handling is minimal (e.g., invalid product ID)


#Credits (if applicable)

  *Developer: Daniel Onyejiekwe
  *Icons: Font Awesome
  *Fonts: Segoe UI (default system font)
  

#how to run/access

1. Transfer the SQL File to Your Laptop

  -Move the stepx_db.sql file to your laptop via:
    USB drive
    Email
    AirDrop / Google Drive / OneDrive


2. Import the Database into MAMP on Your Device

  -On your Device:

  -Open MAMP and start the servers.

  -Open phpMyAdmin:
    http:8888//localhost/phpMyAdmin

  -Click New on the left sidebar to create a new database.

  -Name it stepx_db (must match your project’s config)

  -Choose collation: utf8mb4_general_ci

  -Click Create

  -Now click on the new stepx_db database.

  -Click the Import tab.

  -Click Choose File and select your stepx_db.sql.

  -Click Go.


3. If Done Correctly:

  -You’ll see a success message.
  -All your tables and data should now be visible in phpMyAdmin under stepx_db  


4. To access your Sign Up page on your local MAMP server, the link usually looks like this:

  -http://localhost:8888/stepx-product-catalog/stepx_signup.php

    -or if your port number is different it might look something like this!:
      -http://localhost/stepx-product-catalog/stepx_signup.php
      -http://localhost:(insert port number here)/stepx-product-catalog/stepx_signup.php


5. Make sure:

  -Your stepx_signup.php file is inside your MAMP htdocs folder (e.g., Applications/MAMP/htdocs/stepx-website/ on Mac or C:\MAMP\htdocs\stepx-website\ on Windows).
  -MAMP servers are running.
