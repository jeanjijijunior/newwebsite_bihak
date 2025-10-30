# Bihak Center Website

Official website for Bihak Center - Empowering young people through development and education.

## Overview

Bihak Center is a non-profit organization dedicated to empowering young people through education, development programs, and mentorship. This website serves as the main platform for showcasing our programs, success stories, and opportunities for youth.

## Features

- **Multilingual Support**: English and French language switching powered by Google Translate
- **Dynamic Content**: Database-driven user stories and testimonials
- **Responsive Design**: Mobile-friendly layout that works across all devices
- **Success Stories**: Showcase of youth empowerment stories
- **Opportunities Portal**: Platform for sharing educational and professional opportunities
- **Contact System**: Easy communication with the organization

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Fonts**: Google Fonts (Rubik, Poppins)
- **Translation**: Google Translate API

## Project Structure

```
.
├── assets/
│   ├── css/           # Stylesheets
│   │   ├── style.css      # Main styles
│   │   └── user-tiles.css # User content tiles
│   ├── images/        # Images and logos
│   └── js/            # JavaScript files
│       ├── translate.js      # Language translation
│       └── scroll-to-top.js  # Scroll functionality
├── config/
│   ├── database.php         # Database connection
│   ├── config.example.php   # Example configuration
│   └── *.php               # Other PHP backend files
├── includes/
│   └── *.sql              # Database schemas
├── public/
│   ├── index.php          # Homepage
│   ├── about.html         # About page
│   ├── work.html          # Our work page
│   ├── contact.html       # Contact page
│   ├── opportunities.html # Opportunities page
│   └── login-join.html    # User account page
├── .gitignore
├── .htaccess
└── README.md
```

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.3 or higher
- Apache/Nginx web server
- Composer (optional, for dependencies)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd bihak-center-website
   ```

2. **Configure the database**
   ```bash
   # Import the database schema
   mysql -u your_username -p bihak < includes/database.sql
   ```

3. **Configure the application**
   ```bash
   # Copy the example config file
   cp config/config.example.php config/config.local.php

   # Edit config.local.php with your settings
   nano config/config.local.php
   ```

4. **Update database credentials**

   Edit `config/config.local.php` and update:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'bihak');
   ```

5. **Set up web server**

   **For Apache:**
   - Point DocumentRoot to the `public/` directory
   - Ensure `.htaccess` is enabled
   - Enable `mod_rewrite` if needed

   **For Nginx:**
   ```nginx
   server {
       listen 80;
       server_name your-domain.com;
       root /path/to/bihak-center-website/public;
       index index.php index.html;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

6. **Set permissions**
   ```bash
   chmod 755 public/
   chmod 644 public/*.php public/*.html
   ```

7. **Access the website**

   Open your browser and navigate to: `http://localhost` or your configured domain

## Database Schema

The main database table used:

### `usagers` table
```sql
CREATE TABLE usagers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

See `includes/database.sql` for complete schema.

## Development

### Adding New Pages

1. Create HTML file in `public/` directory
2. Include consistent header and footer
3. Link CSS from `../assets/css/`
4. Link JavaScript from `../assets/js/`

### Modifying Styles

- Main styles: `assets/css/style.css`
- User tiles: `assets/css/user-tiles.css`
- Add new CSS files and link in HTML `<head>`

### Adding JavaScript Features

1. Create new JS file in `assets/js/`
2. Include proper documentation
3. Link in HTML before `</body>`

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Security Considerations

- Database credentials are stored in `config.local.php` (not tracked by Git)
- User input is sanitized using `htmlspecialchars()`
- SQL queries use prepared statements where applicable
- Error reporting is disabled in production mode

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## License

Copyright © 2025 Bihak Center. All Rights Reserved.

## Contact

- **Website**: [bihakcenter.org](http://bihakcenter.org)
- **Email**: contact@bihakcenter.org
- **Facebook**: [Bihak Center](https://facebook.com/bihakcenter)
- **Instagram**: [@bihakcenter](https://instagram.com/bihakcenter)
- **Twitter**: [@bihak_center](https://twitter.com/bihak_center)

## Acknowledgments

- Google Fonts for typography
- Google Translate API for multilingual support
- All contributors and supporters of Bihak Center

---

**Built with care for the youth of tomorrow** ✨
