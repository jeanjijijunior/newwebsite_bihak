# Bihak Center - Complete Setup Guide

## What You Now Have

A **professional, mobile-responsive website** with:

✅ **User Registration System** - Young people can share their stories
✅ **Dynamic Homepage** - Displays profiles from database with "Load More"
✅ **Profile Detail Pages** - Full story view with social media links
✅ **Admin Dashboard** - (In progress) Approve/reject profiles
✅ **Mobile Responsive** - Works perfectly on phones and tablets
✅ **File Uploads** - Images and videos
✅ **8 Fictive Profiles** - Pre-loaded for demonstration
✅ **Modern UI/UX** - Professional design with animations

## Quick Start (5 Minutes)

### Step 1: Install XAMPP

1. Download from: https://www.apachefriends.org/
2. Install to `C:\xampp`
3. Start Apache and MySQL from XAMPP Control Panel

### Step 2: Copy Project

Copy this entire folder to:
```
C:\xampp\htdocs\bihak-center\
```

### Step 3: Create Database

1. Open: http://localhost/phpmyadmin
2. Create database: `bihak`
3. Import: `includes/profiles_schema.sql`

### Step 4: Access Website

Open: **http://localhost/bihak-center/public/index_new.php**

## File Structure

```
bihak-center/
├── public/               # Website pages
│   ├── index_new.php    # Homepage (NEW - use this one!)
│   ├── signup.php       # User registration
│   ├── profile.php      # Profile detail page
│   ├── profiles.php     # AJAX API for loading profiles
│   └── process_signup.php
├── assets/
│   ├── css/
│   │   ├── style.css        # Main styles
│   │   ├── signup.css       # Signup form styles
│   │   ├── profiles.css     # Homepage profile cards
│   │   └── responsive.css   # Mobile responsive
│   ├── js/
│   │   ├── signup-validation.js
│   │   ├── profiles-loader.js  # Load More functionality
│   │   └── scroll-to-top.js
│   ├── images/          # All images
│   └── uploads/         # User uploaded files (created automatically)
├── config/
│   ├── database.php     # Database connection
│   └── config.local.php # Your settings (create this)
└── includes/
    └── profiles_schema.sql  # Database with 8 fictive profiles
```

## Key Pages

### For Users:
- **Homepage**: `index_new.php` - Browse all stories
- **Sign Up**: `signup.php` - Share your story
- **Profile**: `profile.php?id=1` - View full profile

### For Admin (Coming Soon):
- **Login**: `admin/login.php`
- **Dashboard**: `admin/dashboard.php`

## Features Explained

### 1. Homepage (index_new.php)

- **Featured Card**: Newest profile shown in larger tile (2x2 grid)
- **Profile Grid**: Other profiles in smaller tiles
- **Load More**: AJAX loading for better performance
- **Mobile Responsive**: Perfect on all devices

### 2. Signup Form (signup.php)

**User fills in:**
- Personal info (name, email, phone, DOB)
- Location (city, district)
- Education (level, institution, field)
- Story (title, short description, full story)
- Goals and achievements
- Profile photo (required)
- Additional media (optional video/image)
- Social media links

**After submission:**
- Profile goes to "pending" status
- Admin reviews in dashboard
- Admin can approve or reject
- User gets email notification (when configured)

### 3. Profile Detail Page (profile.php)

Shows:
- Large profile photo
- Full story
- Goals and achievements
- Education info
- Social media links
- View count
- Support/contact button

### 4. Admin Dashboard (admin/dashboard.php - In Progress)

Admin can:
- View all pending profiles
- See profile details
- Approve or reject
- Write rejection reason
- Publish approved profiles

## Database

**Tables:**
- `profiles` - User profiles
- `admins` - Admin users
- `profile_media` - Additional media files

**Default Admin:**
- Username: `admin`
- Password: `admin123` (CHANGE THIS!)

## Configuration

Edit `config/config.local.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Your MySQL password
define('DB_NAME', 'bihak');
```

## Testing on Mobile Phone

1. Get your computer's IP:
   ```cmd
   ipconfig
   ```
   Look for IPv4 Address (e.g., `192.168.1.10`)

2. On phone (same WiFi):
   ```
   http://192.168.1.10/bihak-center/public/index_new.php
   ```

## Fictive Profiles Included

8 pre-loaded profiles:
1. Amara Uwase - Software Developer
2. Jean Paul Nkunda - Environmental Activist
3. Grace Mutesi - Artist
4. Emmanuel Hakizimana - Engineer
5. Diane Ingabire - Fashion Entrepreneur
6. Patrick Mugabo - Coding Teacher
7. Marie Claire Uwera - Agribusiness Leader
8. Samuel Ndayishimiye - Veterinarian

All are approved and published, ready to view!

## Common Tasks

### Add New Profile (As User)
1. Go to `signup.php`
2. Fill form
3. Upload image
4. Submit
5. Wait for admin approval

### Approve Profile (As Admin)
1. Login to admin dashboard
2. View pending profiles
3. Click "Approve"
4. Profile appears on homepage

### Change Homepage
Replace `index.php` with `index_new.php`:
```cmd
cd C:\xampp\htdocs\bihak-center\public
rename index.php index_old.php
rename index_new.php index.php
```

## Troubleshooting

### Images Not Showing
- Check file paths in database
- Verify uploads folder exists: `assets/uploads/profiles/`
- Check file permissions

### Profile Not Showing on Homepage
- Check profile status is "approved" in database
- Check is_published is TRUE
- Run SQL:
  ```sql
  UPDATE profiles SET status='approved', is_published=1 WHERE id=1;
  ```

### Load More Not Working
- Check browser console for errors
- Verify `profiles.php` is accessible
- Check database connection

### Form Submit Fails
- Check PHP error log: `C:\xampp\apache\logs\error.log`
- Verify uploads folder is writable
- Check file size limits in `php.ini`

## Next Steps

1. **Test Everything**:
   - Submit a test profile
   - View on homepage
   - Click to see details
   - Test on mobile

2. **Customize**:
   - Update colors in CSS
   - Change images
   - Modify text content

3. **Complete Admin Dashboard**:
   - Build full admin interface
   - Add approval workflow
   - Email notifications

4. **Deploy Online**:
   - Get hosting (Hostinger, Bluehost, etc.)
   - Upload files via FTP
   - Import database
   - Update config

## Push to GitHub

Run:
```cmd
push-to-github.bat
```

Follow prompts to push everything to your GitHub account!

## Need Help?

- Check **XAMPP-SETUP.md** for XAMPP issues
- Check **README.md** for general documentation
- Check **WINDOWS-SETUP.md** for Windows-specific help

## Technology Used

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 8+
- **Database**: MySQL 8+
- **Server**: Apache (via XAMPP)
- **Design**: Mobile-first responsive

## Why These Technologies?

✅ **Easy to learn** - Great for beginners
✅ **Cheap hosting** - $3-5/month
✅ **Works everywhere** - Any hosting supports PHP/MySQL
✅ **Large community** - Easy to find help
✅ **Mobile-ready** - Responsive design included
✅ **No dependencies** - No npm, no build tools

## Alternative: Modern Stack (Future Upgrade)

If you want to upgrade later:
- **Frontend**: React or Vue.js
- **Backend**: Node.js + Express
- **Database**: MongoDB
- **Hosting**: Vercel, Netlify

But start with PHP/MySQL - it's simpler and works great!

---

**Your website is ready!** 🎉

Start XAMPP, open the homepage, and see your profiles in action!
