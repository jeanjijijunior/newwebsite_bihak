# Opportunities Scraper

Automated scraper to collect opportunities from various sources and post them to the Bihak Center website.

## Features

- Scrapes opportunities from multiple sources
- Stores data in MySQL database
- Prevents duplicates
- Runs in headless mode
- Logging for monitoring
- Configurable sources

## Requirements

```bash
pip install selenium mysql-connector-python
```

You'll also need:
- Chrome browser
- ChromeDriver (matching your Chrome version)

## Installation

1. Install Python dependencies:
   ```bash
   pip install -r requirements.txt
   ```

2. Download ChromeDriver:
   - Visit: https://chromedriver.chromium.org/downloads
   - Download version matching your Chrome browser
   - Add to PATH or place in project root

3. Configure database:
   - Update `DB_CONFIG` in `opportunities_scraper.py`
   - Ensure database exists

## Configuration

Edit `opportunities_scraper.py` and update the `SOURCES` list:

```python
SOURCES = [
    {
        'name': 'Source Name',
        'url': 'https://example.com/opportunities',
        'selectors': {
            'container': '.opportunity-list',
            'title': '.opportunity-title',
            'description': '.opportunity-description',
            'link': '.opportunity-link'
        }
    }
]
```

## Usage

### Manual Run

```bash
python opportunities_scraper.py
```

### Automated Daily Run (Linux/Mac)

Add to crontab:
```bash
# Run daily at 6 AM
0 6 * * * cd /path/to/scripts/scrapers && python opportunities_scraper.py
```

### Automated Daily Run (Windows)

Use Task Scheduler:
1. Open Task Scheduler
2. Create Basic Task
3. Set trigger: Daily
4. Set action: Start a program
   - Program: `python`
   - Arguments: `opportunities_scraper.py`
   - Start in: `C:\path\to\scripts\scrapers`

## Database Schema

The scraper creates this table:

```sql
CREATE TABLE opportunities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    url VARCHAR(500),
    source VARCHAR(100),
    scraped_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    UNIQUE KEY unique_opportunity (title, source)
);
```

## Logs

Logs are saved to `scraper.log` in the same directory.

## Troubleshooting

**ChromeDriver version mismatch:**
```bash
# Check Chrome version
google-chrome --version

# Download matching ChromeDriver
```

**Selector not found:**
- Inspect the website structure
- Update CSS selectors in SOURCES configuration
- Check if website uses dynamic loading (may need to adjust wait times)

**Database connection failed:**
- Verify database credentials
- Ensure MySQL server is running
- Check database permissions

## Adding New Sources

1. Inspect the target website
2. Identify CSS selectors for:
   - Container element
   - Title
   - Description
   - Link
3. Add to `SOURCES` list
4. Test with manual run

## Example Sources to Add

Consider adding these types of sources:
- Scholarship websites
- Job boards
- Internship platforms
- Grant opportunities
- Training programs
- Competition announcements

## Security Notes

- Don't commit database credentials
- Respect website robots.txt
- Add delays between requests if needed
- Consider API access when available
