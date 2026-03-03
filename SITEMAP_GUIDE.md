# 🗺️ Sitemap Implementation Guide - Live 18 India

## Overview
Complete dynamic sitemap system with SEO metadata for all pages, news articles, videos, and categories.

## Files Created

### 1. sitemap-generator.php
**Purpose:** Dynamic sitemap generator that creates XML with all content from database

**Features:**
- ✅ All static pages (homepage, about, contact, etc.)
- ✅ All news articles from database
- ✅ All videos from database
- ✅ All categories from database
- ✅ Google News tags for articles
- ✅ Image sitemap for all images
- ✅ Proper priority and change frequency
- ✅ Last modified dates from database

### 2. update-sitemap.php
**Purpose:** Web interface to generate/update sitemap.xml file

**How to Use:**
1. Open browser: `http://your-domain.com/update-sitemap.php`
2. Click to generate sitemap
3. View statistics and preview
4. Sitemap.xml will be automatically created/updated

### 3. sitemap.xml
**Purpose:** Static XML file for search engines

**Update Methods:**
- **Manual:** Run `update-sitemap.php` in browser
- **Automatic:** Set up cron job (see below)
- **On-demand:** Call sitemap-generator.php directly

## Sitemap Structure

### Pages Included

#### High Priority (1.0 - 0.9)
- Homepage (/) - hourly updates
- index.php - hourly updates
- latest.php - hourly updates
- videos.php - daily updates

#### Medium Priority (0.8 - 0.7)
- about.php - monthly updates
- contact.php - monthly updates
- All categories - daily updates
- All news articles - weekly updates

#### Low Priority (0.6 - 0.5)
- search.php - weekly updates
- sitemap.php - weekly updates
- Legal pages (privacy, terms, cookies) - yearly updates
- All videos - monthly updates

## SEO Metadata Included

### For News Articles
```xml
<url>
    <loc>URL</loc>
    <lastmod>Date</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
    
    <!-- Image metadata -->
    <image:image>
        <image:loc>Image URL</image:loc>
        <image:title>Article Title</image:title>
        <image:caption>Article Description</image:caption>
    </image:image>
    
    <!-- Google News metadata -->
    <news:news>
        <news:publication>
            <news:name>Live 18 India</news:name>
            <news:language>hi</news:language>
        </news:publication>
        <news:publication_date>Date</news:publication_date>
        <news:title>Article Title</news:title>
        <news:keywords>Keywords</news:keywords>
    </news:news>
</url>
```

### For Videos
```xml
<url>
    <loc>Video URL</loc>
    <lastmod>Date</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
    
    <image:image>
        <image:loc>Thumbnail URL</image:loc>
        <image:title>Video Title</image:title>
    </image:image>
</url>
```

## Automatic Updates (Cron Job)

### Setup Cron Job (Linux/cPanel)

**Option 1: Using wget**
```bash
# Update sitemap every hour
0 * * * * wget -O /dev/null https://live18india.com/update-sitemap.php
```

**Option 2: Using curl**
```bash
# Update sitemap every hour
0 * * * * curl -s https://live18india.com/update-sitemap.php > /dev/null
```

**Option 3: Using PHP CLI**
```bash
# Update sitemap every hour
0 * * * * php /path/to/your/site/update-sitemap.php > /dev/null
```

### Setup Scheduled Task (Windows)

1. Open Task Scheduler
2. Create Basic Task
3. Set trigger: Daily at specific time
4. Action: Start a program
5. Program: `C:\xampp\php\php.exe`
6. Arguments: `C:\xampp\htdocs\live18india\update-sitemap.php`

## Submit to Search Engines

### Google Search Console
1. Go to: https://search.google.com/search-console
2. Select your property
3. Go to "Sitemaps" in left menu
4. Enter: `sitemap.xml`
5. Click "Submit"

### Bing Webmaster Tools
1. Go to: https://www.bing.com/webmasters
2. Select your site
3. Go to "Sitemaps"
4. Enter: `https://live18india.com/sitemap.xml`
5. Click "Submit"

### Yandex Webmaster
1. Go to: https://webmaster.yandex.com
2. Add your site
3. Go to "Indexing" → "Sitemap files"
4. Add: `https://live18india.com/sitemap.xml`

## Verification

### Check Sitemap Validity
1. **Google Sitemap Validator:** https://www.xml-sitemaps.com/validate-xml-sitemap.html
2. **Bing Sitemap Validator:** Use Bing Webmaster Tools
3. **Manual Check:** Open sitemap.xml in browser

### Monitor Indexing
- Google Search Console → Coverage report
- Bing Webmaster → URL Inspection
- Check indexed pages: `site:live18india.com` in Google

## Troubleshooting

### Sitemap Not Updating
1. Check file permissions (sitemap.xml should be writable)
2. Run update-sitemap.php manually
3. Check PHP error logs
4. Verify database connection

### Search Engines Not Reading Sitemap
1. Verify sitemap.xml is accessible: `https://live18india.com/sitemap.xml`
2. Check robots.txt has sitemap reference
3. Resubmit in Search Console
4. Wait 24-48 hours for crawling

### XML Parsing Errors
1. Validate XML syntax
2. Check for special characters in titles (should be escaped)
3. Verify all URLs are properly encoded
4. Check image URLs are valid

## Best Practices

### Update Frequency
- **News Articles:** Update sitemap when new article is published
- **Videos:** Update when new video is added
- **Static Pages:** Update monthly or when content changes
- **Categories:** Update when new category is added

### Priority Guidelines
- Homepage: 1.0
- Main sections: 0.9
- Category pages: 0.8
- Articles: 0.7
- Videos: 0.6
- Legal pages: 0.5

### Change Frequency
- Breaking news: hourly
- Regular news: daily
- Videos: weekly
- Static pages: monthly
- Legal pages: yearly

## Integration with Admin Panel

### Auto-update on Content Changes

Add this code to your admin panel after saving news/videos:

```php
// After saving news article
if ($stmt->execute()) {
    // Update sitemap
    file_get_contents('https://live18india.com/update-sitemap.php');
    
    // Or use local file
    ob_start();
    include '../update-sitemap.php';
    ob_end_clean();
}
```

## Statistics & Monitoring

### Track Sitemap Performance
- Monitor in Google Search Console
- Check indexed pages count
- Review crawl stats
- Monitor coverage issues

### Expected Results
- **Total URLs:** 500-2000 (depending on content)
- **News Articles:** Updated daily
- **Indexing Time:** 1-7 days for new content
- **Coverage:** 80-95% of submitted URLs

## Maintenance

### Weekly Tasks
- [ ] Check sitemap.xml is accessible
- [ ] Verify new content is included
- [ ] Review Search Console for errors

### Monthly Tasks
- [ ] Update static page priorities if needed
- [ ] Review and optimize change frequencies
- [ ] Check for broken URLs
- [ ] Verify image URLs are working

### Quarterly Tasks
- [ ] Full sitemap audit
- [ ] Review SEO performance
- [ ] Update metadata if needed
- [ ] Optimize for new Google algorithms

## Support & Resources

### Useful Links
- Google Sitemap Protocol: https://www.sitemaps.org/
- Google News Sitemap: https://support.google.com/news/publisher-center/answer/9606710
- Image Sitemap: https://developers.google.com/search/docs/advanced/sitemaps/image-sitemaps

### Tools
- XML Sitemap Validator: https://www.xml-sitemaps.com/validate-xml-sitemap.html
- Sitemap Generator: Already implemented (sitemap-generator.php)
- Sitemap Tester: Google Search Console

---

**Last Updated:** February 28, 2026  
**Status:** ✅ Production Ready  
**Version:** 1.0
