"""
Weekly crawler scheduler — runs Sunday 02:00 WIB.
Also purges expired sessions on daily tick.
"""

import os
import asyncio
from apscheduler.schedulers.background import BackgroundScheduler
from loguru import logger

from db import SessionLocal, purge_expired_sessions


def _run_crawlers():
    """Sync wrapper to run async crawlers."""
    from crawler.icct_crawler  import crawl_icct
    from crawler.unodc_crawler import crawl_unodc

    db = SessionLocal()
    try:
        loop = asyncio.new_event_loop()
        asyncio.set_event_loop(loop)
        results = loop.run_until_complete(crawl_icct(db))
        results += loop.run_until_complete(crawl_unodc(db))
        logger.info(f"Scheduled crawl complete — {len(results)} new documents.")
    except Exception as e:
        logger.error(f"Crawl error: {e}")
    finally:
        db.close()


def start_scheduler():
    schedule = os.getenv("CRAWLER_SCHEDULE", "0 2 * * 0")
    parts    = schedule.split()

    scheduler = BackgroundScheduler()
    scheduler.add_job(
        _run_crawlers,
        trigger="cron",
        day_of_week="sun",
        hour=int(parts[1]) if len(parts) > 1 else 2,
        minute=int(parts[0]) if parts else 0,
        id="weekly_crawl",
    )
    scheduler.add_job(
        purge_expired_sessions,
        trigger="cron",
        hour=3,
        minute=0,
        id="daily_purge",
    )
    scheduler.start()
    logger.info("Crawler scheduler started (weekly Sunday 02:00).")
