# Multi-Content CMS Installation Guide

## Overview

The Multi-Content Management System now features a modern, secure installation process similar to WordPress. This guide will walk you through the complete installation process.

## Prerequisites

Before installing, ensure your server meets these requirements:

- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher (or MariaDB 10.2+)
- **Extensions**: mysqli, GD, cURL
- **Permissions**: Writable `includes/` and `uploads/` directories
- **Web Server**: Apache (with mod_rewrite) or Nginx

## Pretty URLs / ready-made packs (Phase 2)

Apache: the bundled `.htaccess` rewrites non-file requests to `index.php?mc_route=...` so an active ready-made pack is served from the site root.

Nginx example (document root = MultiCMS root):

```nginx
location / {
    try_files $uri $uri/ /index.php?mc_route=$uri&$args;
}
location ~ ^/includes/.*\.php$ { deny all; }
location ~* /images/.*\.(php|phtml|phar)$ { deny all; }
```

After install, manage core content at `/administrator/posts.php`, site metadata at `/administrator/settings_core.php`, and ready-made packs at `/administrator/plugins_prebuilt_sites.php`. Prefer **Fresh default**; use Ready Sites only when you accept legacy-pack risk.

## Installation Process

### Step 1: Upload Files

1. Upload all CMS files to your web server
2. Ensure the `uploads/` directory is writable
3. Ensure the `includes/` directory is writable

### Step 2: Run Installation

1. Navigate to `yourdomain.com/install.php`
2. The installation wizard will guide you through the process

### Step 3: Installation Steps

#### Welcome
- Introduction to the installation process
- Click "Get Started" to begin

#### System Requirements
- Automatic check of PHP version, extensions, and permissions
- All requirements must be met before proceeding
- Click "Continue to Database Setup" when ready

#### Database Configuration
- **Database Host**: Usually `localhost` or your database server IP
- **Username**: Your MySQL username
- **Password**: Your MySQL password
- **Database Name**: The database you want to use (will be created if it doesn't exist)
- Click "Test Connection & Continue" to verify and proceed

#### Site Configuration
- **Site Title**: Your website's name
- **Site Description**: Brief description of your site
- **Start mode**: Fresh default, or a ready-made site plugin (blog, marketplace, doctors, …). Switch later from Admin → Ready-made Sites.
- **Admin Email**: Primary administrator email address
- **Timezone**: Select your local timezone
- Click "Continue to Admin Setup"

#### Administrator Setup
- **Username**: Choose a unique admin username
- **Password**: Create a strong password (minimum 8 characters)
- **Confirm Password**: Re-enter your password
- Click "Continue to Installation"

#### Installation
- System automatically creates database tables
- Populates initial data
- Creates configuration files
- Sets up administrator account
- Creates basic content structure

#### Complete
- Installation finished successfully
- Access your admin panel or view your site
- **Important**: Delete `install.php` for security

## Post-Installation

### 1. First Login
- Go to `yourdomain.com/administrator/`
- Login with your admin credentials
- Change your password immediately

### 2. Site Configuration
- Navigate to Settings in admin panel
- Configure site title, description, and other options
- Set up your preferred theme
- Configure email settings

### 3. Content Setup
- Create your first blog post or page
- Set up categories
- Upload and organize media files
- Configure navigation menus

### 4. Security Cleanup
- Delete `install.php` file
- Delete `install_complete.php` file
- Ensure `.htaccess` is properly configured
- Set up SSL certificate (recommended)

## File Structure After Installation

```
your-project/
├── includes/
│   ├── installed.lock          # Installation lock file
│   ├── db_config.php          # Database configuration
│   ├── bootstrap.php          # Application bootstrap
│   ├── Database.php           # Database class
│   ├── Session.php            # Session management
│   ├── Validator.php          # Input validation
│   ├── ErrorHandler.php       # Error handling
│   └── services/              # Service classes
├── themes/
│   └── default/               # Default theme
├── uploads/                   # File uploads directory
├── administrator/             # Admin panel
├── index.php                  # Main entry point
└── .htaccess                  # Security and routing
```

## Troubleshooting

### Common Issues

#### Database Connection Failed
- Verify database credentials
- Ensure MySQL service is running
- Check if database exists or can be created
- Verify user permissions

#### Permission Errors
- Ensure `includes/` directory is writable (755 or 775)
- Ensure `uploads/` directory is writable (755 or 775)
- Check web server user permissions

#### Installation Won't Complete
- Check error logs in `logs/error.log`
- Verify all requirements are met
- Ensure sufficient disk space
- Check PHP memory limits

#### Can't Access Admin Panel
- Verify administrator account was created
- Check if `installed.lock` file exists
- Ensure proper file permissions
- Check for JavaScript errors in browser console

### Error Logs

The system logs errors to `logs/error.log`. Check this file if you encounter issues:

```bash
tail -f logs/error.log
```

## Security Considerations

### During Installation
- Use strong passwords
- Ensure secure database credentials
- Run on HTTPS if possible

### After Installation
- Delete installation files
- Change default admin password
- Set up SSL certificate
- Configure firewall rules
- Regular security updates

## Backup and Migration

### Before Installation
- Backup existing database (if upgrading)
- Backup existing files
- Document current configuration

### After Installation
- Backup new database
- Backup configuration files
- Document new setup

## Support

If you encounter issues:

1. Check this installation guide
2. Review error logs
3. Verify system requirements
4. Check file permissions
5. Consult system administrator

## Advanced Configuration

### Custom Database Prefix
Edit `includes/db_config.php` to add table prefixes:

```php
define('DB_PREFIX', 'cms_');
```

### Custom Upload Directory
Modify upload paths in admin panel or edit configuration files.

### Custom Theme
Upload custom themes to `themes/` directory and activate in admin panel.

## Performance Optimization

### Database
- Run database optimization after installation
- Set up regular maintenance schedules
- Monitor query performance

### Caching
- Enable PHP OPcache
- Configure web server caching
- Consider Redis/Memcached for session storage

### File System
- Optimize image uploads
- Use CDN for static assets
- Regular cleanup of temporary files

---

**Note**: This installation system is designed to run only once. After successful installation, the system will redirect to the main site and prevent re-running the installer.
