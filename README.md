# 🏛️ Barangay Document Request System
## VS Code Organized Version

Complete web-based document request management system for barangay offices with **separated files** for easy editing in VS Code.

---

## 📁 PROJECT STRUCTURE

```
barangay-vscode/
│
├── 📂 css/                    # All CSS Files (Separated by purpose)
│   ├── style.css             # Main styles, variables, layout
│   ├── components.css        # Buttons, forms, tables, cards
│   ├── login.css             # Login/auth pages styles
│   └── responsive.css        # Mobile & tablet responsive
│
├── 📂 database/               # Database SQL Files
│   ├── schema.sql            # Database structure (tables)
│   └── sample-data.sql       # Sample data to test
│
├── 📂 includes/               # Reusable PHP Files
│   ├── config.php            # Database connection
│   ├── functions.php         # Helper functions
│   ├── header.php            # HTML head & CSS links
│   └── footer.php            # Closing tags & scripts
│
├── 📂 user/                   # User/Resident Pages
│   ├── dashboard.php         # User dashboard
│   ├── request.php           # Submit new request
│   └── my-requests.php       # View all requests
│
├── 📂 admin/                  # Admin Pages
│   ├── login.php             # Admin login
│   ├── dashboard.php         # Admin dashboard
│   ├── requests.php          # All requests management
│   ├── view-request.php      # View & update request
│   ├── users.php             # Users management
│   └── documents.php         # Document types CRUD
│
├── 📂 js/                     # JavaScript Files (optional)
│
├── index.php                  # Landing page
├── login.php                  # User login
├── register.php               # User registration
├── logout.php                 # Logout handler
└── README.md                  # This file
```

---

## 🚀 INSTALLATION GUIDE

### **STEP 1: Download Project**
1. Download the ZIP file
2. Extract to your desired location
3. Rename folder to `barangay-vscode` (optional)

### **STEP 2: Install XAMPP**
1. Download from: https://www.apachefriends.org/
2. Install (default settings)
3. Installation path: `C:\xampp\`

### **STEP 3: Copy to XAMPP**
1. Copy `barangay-vscode` folder
2. Paste to: `C:\xampp\htdocs\`
3. Final path: `C:\xampp\htdocs\barangay-vscode\`

### **STEP 4: Start XAMPP Services**
1. Open **XAMPP Control Panel**
2. Click **Start** for **Apache** ✅
3. Click **Start** for **MySQL** ✅

### **STEP 5: Create Database**

**Option A: Using Import (Recommended)**
1. Go to: `http://localhost/phpmyadmin`
2. Click **"New"** → Create database: `barangay_system`
3. Click the database
4. Click **"Import"** tab
5. Click **"Choose File"**
6. Select: `database/schema.sql`
7. Click **"Import"**
8. Repeat for: `database/sample-data.sql`

**Option B: Using SQL Tab**
1. Go to: `http://localhost/phpmyadmin`
2. Click **"SQL"** tab
3. Open `database/schema.sql` in notepad
4. Copy all content → Paste → Click **"Go"**
5. Repeat for `database/sample-data.sql`

### **STEP 6: Configure Database (if needed)**
Edit: `includes/config.php`
```php
$servername = "localhost";
$username = "root";
$password = "";  // blank for XAMPP
$dbname = "barangay_system";
```

### **STEP 7: Access the System**
Open browser:
- **Main:** http://localhost/barangay-vscode/
- **User:** http://localhost/barangay-vscode/login.php
- **Admin:** http://localhost/barangay-vscode/admin/login.php

---

## 🔐 DEMO ACCOUNTS

### User/Resident
- **Email:** maria@gmail.com
- **Password:** password123

### Admin
- **Username:** admin
- **Password:** admin123

---

## 📝 FILE EXPLANATIONS

### **CSS Files** (in `/css/` folder)

**`style.css`** - Main stylesheet
- CSS variables (colors, spacing, shadows)
- Reset styles
- Layout containers
- Header & navigation

**`components.css`** - UI Components
- Statistics cards
- Forms & inputs
- Buttons
- Tables
- Status badges
- Alerts

**`login.css`** - Authentication pages
- Login container
- Registration forms
- Login tabs

**`responsive.css`** - Mobile support
- Tablet styles (768px)
- Mobile styles (480px)
- Print styles

### **Database Files** (in `/database/` folder)

**`schema.sql`** - Database structure
- Creates 4 tables
- Sets up relationships
- Adds indexes

**`sample-data.sql`** - Test data
- 3 admin accounts
- 7 document types
- 4 user accounts
- 8 sample requests

### **Includes Files** (in `/includes/` folder)

**`config.php`** - Database connection
```php
$conn = new mysqli($servername, $username, $password, $dbname);
```

**`functions.php`** - Helper functions
- `sanitize_input()` - Clean user input
- `redirect()` - Page redirection
- `format_date()` - Date formatting
- `get_status_badge()` - Status display
- `hash_password()` - Password hashing
- And more...

**`header.php`** - HTML header
- `<!DOCTYPE>`, `<head>` tags
- CSS links
- Meta tags

**`footer.php`** - HTML footer
- Closing `</body>` `</html>` tags
- JavaScript (if needed)

---

## 🎨 CUSTOMIZATION GUIDE

### **Change Colors**
Edit: `css/style.css` (lines 10-30)
```css
:root {
    --primary-color: #667eea;  /* Change this */
    --success-color: #48bb78;  /* And this */
}
```

### **Change Database Name**
1. Edit: `includes/config.php`
2. Change: `$dbname = "your_new_name";`
3. Update in phpMyAdmin

### **Add New Page**
1. Copy existing PHP file
2. Include header: `include 'includes/header.php';`
3. Include footer: `include 'includes/footer.php';`
4. Use CSS: `<link rel="stylesheet" href="css/style.css">`

---

## 💻 EDITING IN VS CODE

### **Open Project**
1. Open VS Code
2. File → Open Folder
3. Select: `C:\xampp\htdocs\barangay-vscode\`

### **Recommended Extensions**
- PHP Intelephense
- HTML CSS Support
- Auto Rename Tag
- Prettier (code formatter)
- Live Server (for HTML preview)

### **File Organization**
```
📝 Need to edit styles? → css/ folder
📝 Need to edit database? → database/ folder
📝 Need to edit user pages? → user/ folder
📝 Need to edit admin pages? → admin/ folder
📝 Need to add functions? → includes/functions.php
```

---

## 🗄️ DATABASE STRUCTURE

### **Tables**

**1. users** - Resident accounts
- id, firstname, lastname, email, password, address, contact_number

**2. admins** - Staff accounts
- id, username, password, fullname

**3. document_types** - Available documents
- id, document_name, requirements, processing_days

**4. document_requests** - All requests
- id, user_id, document_type_id, purpose, status, request_date, remarks

---

## ✨ FEATURES

### **CRUD Operations**
- ✅ **CREATE:** Submit requests, register users
- ✅ **READ:** View dashboard, requests, users
- ✅ **UPDATE:** Change status, edit info
- ✅ **DELETE:** Remove document types

### **Search & Filter**
- Search by name, email, document
- Filter by status (pending/processing/approved/rejected)

### **User Roles**
- **Resident:** Submit & track requests
- **Admin:** Manage all requests & users

---

## 🐛 TROUBLESHOOTING

### ❌ White/Blank Page
**Fix:** Check PHP errors
1. Edit `includes/config.php`
2. Add at top: `ini_set('display_errors', 1);`

### ❌ CSS Not Loading
**Fix:** Check file paths
- User pages: `../css/style.css`
- Root pages: `css/style.css`

### ❌ Database Connection Error
**Fix:** Verify credentials
1. Check MySQL is running (green in XAMPP)
2. Verify database name: `barangay_system`
3. Check `includes/config.php`

### ❌ 404 Not Found
**Fix:** Check folder location
- Must be in: `C:\xampp\htdocs\barangay-vscode\`
- Access via: `http://localhost/barangay-vscode/`

---

## 📞 SUPPORT

### Common Issues:
1. **Apache won't start** → Port 80 occupied (close Skype)
2. **MySQL won't start** → Port 3306 occupied
3. **Page not found** → Wrong folder location
4. **Login not working** → Database not imported

---

## 📚 LEARNING RESOURCES

### For Beginners:
- **HTML/CSS:** w3schools.com
- **PHP:** php.net/manual
- **MySQL:** dev.mysql.com/doc

### For This Project:
- All CSS variables in: `css/style.css`
- All functions in: `includes/functions.php`
- Database structure in: `database/schema.sql`

---

## 📌 NOTES

✅ Passwords are hashed with `password_hash()`  
✅ SQL uses prepared statements (secure)  
✅ Responsive design (mobile-friendly)  
✅ Commented code for learning  
✅ Separated files for organization  

---

**Version:** 2.0 (VS Code Organized)  
**Last Updated:** 2024  
**Made for:** Educational purposes & learning  

**Happy Coding!** 🚀
