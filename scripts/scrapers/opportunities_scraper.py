#!/usr/bin/env python3
"""
Bihak Center - Opportunities Scraper
Scrapes opportunities from various sources and stores them in the database.
Run this script daily via cron job.
"""

import os
import sys
from datetime import datetime
from typing import List, Dict
import logging

# Add parent directory to path for imports
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

try:
    from selenium import webdriver
    from selenium.webdriver.chrome.service import Service
    from selenium.webdriver.chrome.options import Options
    from selenium.webdriver.common.by import By
    from selenium.webdriver.support.ui import WebDriverWait
    from selenium.webdriver.support import expected_conditions as EC
    import mysql.connector
    from mysql.connector import Error
except ImportError as e:
    print(f"Error: Missing required package - {e}")
    print("Install required packages: pip install selenium mysql-connector-python")
    sys.exit(1)

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('scraper.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Database configuration
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'bihak'
}

# Websites to scrape
SOURCES = [
    {
        'name': 'Example Job Board',
        'url': 'https://example.com/opportunities',
        'selectors': {
            'container': '.opportunity-list',
            'title': '.opportunity-title',
            'description': '.opportunity-description',
            'link': '.opportunity-link'
        }
    }
    # Add more sources as needed
]


class OpportunityScraper:
    """Scrapes opportunities from various sources"""

    def __init__(self):
        """Initialize the scraper"""
        self.driver = None
        self.opportunities = []

    def setup_driver(self):
        """Set up Selenium WebDriver with Chrome"""
        try:
            chrome_options = Options()
            chrome_options.add_argument('--headless')  # Run in background
            chrome_options.add_argument('--no-sandbox')
            chrome_options.add_argument('--disable-dev-shm-usage')
            chrome_options.add_argument('--disable-gpu')
            chrome_options.add_argument('--window-size=1920,1080')

            # Use chromedriver (ensure it's in PATH or specify path)
            self.driver = webdriver.Chrome(options=chrome_options)
            logger.info("WebDriver initialized successfully")
        except Exception as e:
            logger.error(f"Failed to initialize WebDriver: {e}")
            raise

    def scrape_source(self, source: Dict) -> List[Dict]:
        """Scrape a single source for opportunities"""
        opportunities = []

        try:
            logger.info(f"Scraping {source['name']} at {source['url']}")
            self.driver.get(source['url'])

            # Wait for content to load
            wait = WebDriverWait(self.driver, 10)
            wait.until(EC.presence_of_element_located(
                (By.CSS_SELECTOR, source['selectors']['container'])
            ))

            # Find all opportunity elements
            containers = self.driver.find_elements(
                By.CSS_SELECTOR, source['selectors']['container']
            )

            for container in containers:
                try:
                    title = container.find_element(
                        By.CSS_SELECTOR, source['selectors']['title']
                    ).text.strip()

                    description = container.find_element(
                        By.CSS_SELECTOR, source['selectors']['description']
                    ).text.strip()

                    link = container.find_element(
                        By.CSS_SELECTOR, source['selectors']['link']
                    ).get_attribute('href')

                    opportunity = {
                        'title': title,
                        'description': description,
                        'url': link,
                        'source': source['name'],
                        'scraped_at': datetime.now()
                    }

                    opportunities.append(opportunity)
                    logger.debug(f"Found opportunity: {title}")

                except Exception as e:
                    logger.warning(f"Error parsing opportunity: {e}")
                    continue

            logger.info(f"Found {len(opportunities)} opportunities from {source['name']}")

        except Exception as e:
            logger.error(f"Error scraping {source['name']}: {e}")

        return opportunities

    def scrape_all(self):
        """Scrape all configured sources"""
        self.setup_driver()

        try:
            for source in SOURCES:
                source_opportunities = self.scrape_source(source)
                self.opportunities.extend(source_opportunities)

            logger.info(f"Total opportunities scraped: {len(self.opportunities)}")

        finally:
            if self.driver:
                self.driver.quit()
                logger.info("WebDriver closed")

    def save_to_database(self):
        """Save scraped opportunities to database"""
        if not self.opportunities:
            logger.warning("No opportunities to save")
            return

        try:
            conn = mysql.connector.connect(**DB_CONFIG)
            cursor = conn.cursor()

            # Create table if it doesn't exist
            cursor.execute("""
                CREATE TABLE IF NOT EXISTS opportunities (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    description TEXT NOT NULL,
                    url VARCHAR(500),
                    source VARCHAR(100),
                    scraped_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    is_active BOOLEAN DEFAULT TRUE,
                    UNIQUE KEY unique_opportunity (title, source)
                )
            """)

            # Insert opportunities
            insert_query = """
                INSERT INTO opportunities (title, description, url, source, scraped_at)
                VALUES (%s, %s, %s, %s, %s)
                ON DUPLICATE KEY UPDATE
                    description = VALUES(description),
                    url = VALUES(url),
                    scraped_at = VALUES(scraped_at),
                    is_active = TRUE
            """

            inserted = 0
            for opp in self.opportunities:
                try:
                    cursor.execute(insert_query, (
                        opp['title'],
                        opp['description'],
                        opp['url'],
                        opp['source'],
                        opp['scraped_at']
                    ))
                    inserted += cursor.rowcount
                except Error as e:
                    logger.warning(f"Error inserting opportunity '{opp['title']}': {e}")

            conn.commit()
            logger.info(f"Successfully saved {inserted} opportunities to database")

        except Error as e:
            logger.error(f"Database error: {e}")
            raise

        finally:
            if conn and conn.is_connected():
                cursor.close()
                conn.close()
                logger.info("Database connection closed")


def main():
    """Main execution function"""
    logger.info("=" * 50)
    logger.info("Starting Opportunities Scraper")
    logger.info("=" * 50)

    try:
        scraper = OpportunityScraper()
        scraper.scrape_all()
        scraper.save_to_database()

        logger.info("Scraping completed successfully")

    except Exception as e:
        logger.error(f"Scraping failed: {e}")
        sys.exit(1)


if __name__ == "__main__":
    main()
