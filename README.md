# Multi-Content Management System

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-Open%20Source-green.svg)](LICENSE.md)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://www.mysql.com/)
[![Maintenance](https://img.shields.io/badge/Maintained-Yes-brightgreen.svg)](https://github.com/Digitalcloud-Ali/Multi-Content-Management-System)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](https://github.com/Digitalcloud-Ali/Multi-Content-Management-System/pulls)

---

## 📑 **Table of Contents**
- [Overview](#-overview)
- [Key Features](#-key-features)
- [Screenshots](#-screenshots)
- [Technical Requirements](#️-technical-requirements)
- [Quick Start](#-quick-start)
- [Code Examples](#-code-examples)
- [Documentation](#-documentation)
- [Customization](#-customization)
- [Demo & Support](#-demo--support)
- [FAQ](#-faq)
- [Contributing](#-contributing)
- [License](#-license)
- [Recent Updates](#-recent-updates)
- [Acknowledgments](#-acknowledgments)

---

## 🚀 **Overview**

The Multi-Content Management System is a **modern, secure, and robust** platform developed with **PHP 8+**, MySQL, HTML5, CSS3, and JavaScript. This system provides a comprehensive solution for building various types of websites with enterprise-grade security and modern architecture.

Built with inspiration from popular content management systems like WordPress, Magento, and Joomla, this platform offers **10+ ready-to-deploy website templates** that can be customized for any business or personal use case.

### **🎯 Why Choose This CMS?**
- ✨ **Modern & Secure** - Built with PHP 8+ and enterprise-grade security
- 🚀 **Easy to Deploy** - WordPress-style installation wizard
- 🎨 **Fully Customizable** - Complete control over design and functionality
- 📱 **Mobile First** - Responsive design works on all devices
- 🔌 **Extensible** - Modular architecture for custom features
- 💰 **Free & Open Source** - No licensing fees or restrictions

## ✨ **Key Features**

### **🔒 Security & Modern Architecture**
- **PHP 8+ Compatible** - Latest security patches and performance
- **Prepared Statements** - SQL injection protection
- **CSRF Protection** - Cross-site request forgery prevention
- **Secure Sessions** - Session fixation protection
- **Input Validation** - Comprehensive sanitization and validation
- **Security Headers** - XSS protection and content security

### **🏗️ Modular Design**
- **Blog System** - Full-featured blogging with categories and tags
- **Video Streaming** - Video upload and streaming capabilities
- **Marketplace** - E-commerce and classified ads functionality
- **Image Gallery** - Professional photo and media management
- **Portfolio** - Showcase projects and work
- **Ad Posting** - Classified advertisements system
- **Search Engine** - Advanced search and filtering
- **Tutorials** - Educational content management
- **Doctors/Healthcare** - Medical practice management
- **Custom Modules** - Extensible architecture for custom needs

### **🎨 Modern Frontend**
- **Bootstrap 5** - Responsive, mobile-first design
- **jQuery 3.7.1** - Latest JavaScript library
- **Font Awesome** - Professional icon library
- **Custom Themes** - Easy theme customization
- **Mobile Responsive** - Works perfectly on all devices

### **⚙️ Administration**
- **User Management** - Role-based access control
- **Content Management** - Easy content creation and editing
- **Media Library** - File upload and organization
- **Analytics** - Built-in statistics and reporting
- **Backup System** - Database and file backup
- **SEO Tools** - Search engine optimization features

## 📸 **Screenshots**

### Admin Dashboard
The system features a modern, intuitive admin panel for managing all aspects of your website.

### Available Modules
- **Blog System** - Full-featured blogging platform
- **Video Streaming** - Upload and stream video content
- **E-commerce/Marketplace** - Sell products online
- **Image Gallery** - Professional photo management
- **Portfolio** - Showcase your work
- **Tutorials** - Educational content system

> **Note:** Live screenshots and demos are available at [multicms.digitalcloud.no](http://multicms.digitalcloud.no)

## 🛠️ **Technical Requirements**

### **Server Requirements**
- **PHP**: 7.4 or higher (PHP 8.0+ recommended)
- **MySQL**: 5.7 or higher (MariaDB 10.2+ supported)
- **Extensions**: mysqli, GD, cURL
- **Web Server**: Apache (with mod_rewrite) or Nginx
- **Memory**: 128MB RAM minimum (256MB recommended)

### **Browser Support**
- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+
- **Mobile**: iOS Safari 14+, Chrome Mobile 90+

## 🚀 **Quick Start**

### **Prerequisites**
Before installing, ensure your server meets these requirements:
- PHP 7.4+ (PHP 8.0+ recommended)
- MySQL 5.7+ or MariaDB 10.2+
- Apache with mod_rewrite or Nginx
- PHP Extensions: mysqli, GD, cURL
- At least 128MB RAM (256MB recommended)

### **Installation Methods**

#### **Method 1: Quick Install (Recommended)**
```bash
# 1. Clone the repository
git clone https://github.com/Digitalcloud-Ali/Multi-Content-Management-System.git

# 2. Navigate to project directory
cd Multi-Content-Management-System

# 3. Set proper permissions
chmod 755 -R .
chmod 777 -R uploads/
chmod 777 -R includes/

# 4. Create database
mysql -u root -p -e "CREATE DATABASE raycms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 5. Open installation wizard in browser
# Visit: http://yourdomain.com/install.php
```

#### **Method 2: Manual Setup**
1. **Upload Files** - Transfer all files to your web server
2. **Create Database** - Create a MySQL database for the CMS
3. **Run Installer** - Navigate to `install.php` in your browser
4. **Follow Wizard** - Complete the installation steps
5. **Delete Installer** - Remove `install.php` for security

### **Post-Installation Steps**
```bash
# 1. Delete installation files (CRITICAL for security)
rm install.php
rm install_complete.php

# 2. Set secure file permissions
chmod 644 includes/config.php
chmod 644 includes/rayicecms.php

# 3. Access your site
# Frontend: http://yourdomain.com
# Admin: http://yourdomain.com/administrator/
```

### **First Time Login**
- **Default Admin URL**: `yourdomain.com/administrator/`
- **Username**: The one you created during installation
- **Password**: The one you set during installation
- ⚠️ **Important**: Change your password immediately after first login!

## 💻 **Code Examples**

### **Creating a Blog Post Programmatically**
```php
<?php
require_once 'includes/raycms.php';
require_once 'includes/modern_functions.php';

// Create a new blog post
$title = sanitize_input($_POST['title']);
$content = sanitize_input($_POST['content']);
$author_id = $_SESSION['user_id'];

$query = "INSERT INTO blog_posts (title, content, author_id, created_at) 
          VALUES (?, ?, ?, NOW())";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $author_id);
mysqli_stmt_execute($stmt);
?>
```

### **User Authentication Check**
```php
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['MM_Username'])) {
    header("Location: login.php");
    exit;
}

// Check if user has admin role
if ($_SESSION['MM_UserGroup'] !== 'administrator') {
    header("Location: access-denied.php");
    exit;
}
?>
```

### **Custom Module Integration**
```php
<?php
// Add custom functionality to the CMS
class CustomModule {
    public function __construct() {
        add_action('init', array($this, 'register_custom_post_type'));
    }
    
    public function register_custom_post_type() {
        // Your custom module code here
    }
}

$custom_module = new CustomModule();
?>
```

## 📚 **Documentation**

Comprehensive documentation is available in the [`/docs`](docs/) folder:

| Document | Description |
|----------|-------------|
| **📖 [Documentation Index](docs/README.md)** | Overview and navigation guide |
| **🚀 [Installation Guide](docs/INSTALLATION_GUIDE.md)** | Complete setup instructions with wizard walkthrough |
| **🔧 [Update Guide](docs/UPDATE_README.md)** | Modernization details, PHP 8+ migration, and changes |
| **🔮 [Advanced Improvements](docs/ADVANCED_IMPROVEMENTS.md)** | Future roadmap, API development, and enhancements |
| **🔒 [Security Policy](SECURITY.md)** | Security guidelines and vulnerability reporting |

## 🔧 **Customization**

### **Themes**
- **Pure HTML/CSS** - Traditional theme development
- **Modern Frameworks** - React, Vue, or Angular integration
- **Bootstrap Themes** - Quick customization with Bootstrap
- **Custom CSS** - Full design control

### **Modules**
- **Extensible Architecture** - Add custom functionality
- **Plugin System** - Modular feature additions
- **API Integration** - Connect with external services
- **Custom Fields** - Flexible content structure

## 🌐 **Demo & Support**

### **Live Demo**
- **Frontend**: [multicms.digitalcloud.no](http://multicms.digitalcloud.no)
- **Admin Panel**: [multicms.digitalcloud.no/administrator/](http://multicms.digitalcloud.no/administrator/)

### **Support Channels**
- 📧 **Email**: hei@digitalcloud.no
- 📖 **Documentation**: Comprehensive guides in [`/docs`](docs/) folder
- 🐛 **Issues**: [GitHub Issues](https://github.com/Digitalcloud-Ali/Multi-Content-Management-System/issues)
- 💬 **Discussions**: [GitHub Discussions](https://github.com/Digitalcloud-Ali/Multi-Content-Management-System/discussions)
- 🔒 **Security**: Report vulnerabilities to hei@digitalcloud.no

### **Getting Help**
1. **Check Documentation** - Most questions are answered in our comprehensive docs
2. **Search Issues** - Someone may have had the same question
3. **Ask the Community** - Open a discussion for general questions
4. **Report Bugs** - Use GitHub Issues for bug reports
5. **Contact Support** - Email us for direct assistance

## ❓ **FAQ**

<details>
<summary><strong>Is this CMS really free to use?</strong></summary>

Yes! This project is completely open source and free to use, modify, and distribute. There are no licensing fees or restrictions.
</details>

<details>
<summary><strong>What's the difference between this and WordPress?</strong></summary>

This CMS is built from scratch with modern PHP 8+ and focuses on providing multiple content types (blog, video, marketplace, etc.) in one platform. It's lighter and more flexible for custom development.
</details>

<details>
<summary><strong>Can I use this for commercial projects?</strong></summary>

Absolutely! You can use this CMS for any purpose, including commercial projects, without any restrictions.
</details>

<details>
<summary><strong>How do I upgrade from an older version?</strong></summary>

1. Backup your database and files
2. Read the [Update Guide](docs/UPDATE_README.md)
3. Replace files (except config files)
4. Run any database migrations
5. Test thoroughly
</details>

<details>
<summary><strong>Is it secure for production use?</strong></summary>

Yes! Version 2.0+ includes:
- SQL injection protection with prepared statements
- CSRF protection
- XSS protection
- Secure session handling
- Input validation and sanitization

Always keep your installation updated for the latest security patches.
</details>

<details>
<summary><strong>Can I migrate from WordPress to this CMS?</strong></summary>

While there's no automated migration tool, you can export your WordPress content and import it into this CMS. Custom migration scripts may be needed for large sites.
</details>

<details>
<summary><strong>What hosting do I need?</strong></summary>

Any shared hosting, VPS, or cloud server that supports:
- PHP 7.4+ (8.0+ recommended)
- MySQL 5.7+
- Apache or Nginx

Most hosting providers (cPanel, Plesk, etc.) work perfectly.
</details>

## 📄 **License**

This project is open source and free to use, modify, and distribute. Users are free to edit, share, or use this project in any of their projects.

**Terms:**
- ✅ Commercial use allowed
- ✅ Modification allowed
- ✅ Distribution allowed
- ✅ Private use allowed
- ❌ No warranty provided
- ❌ No liability accepted

See [LICENSE.md](LICENSE.md) for full details.

## 🚀 **Recent Updates**

### **v2.0 - Major Modernization (2024)**
- ✅ **PHP 8+ Compatibility** - Full modern PHP support
- ✅ **Security Overhaul** - SQL injection, XSS, CSRF protection
- ✅ **Modern Architecture** - Service layer and dependency injection
- ✅ **Updated Dependencies** - Latest jQuery, Bootstrap, and libraries
- ✅ **Installation Wizard** - WordPress-style automated setup
- ✅ **Performance Improvements** - Optimized database and caching
- ✅ **Mobile First** - Responsive design for all devices

### **Future Roadmap**
- 🔄 **API Development** - RESTful API for mobile apps
- 🔄 **Testing Framework** - Unit and integration tests
- 🔄 **CI/CD Pipeline** - Automated testing and deployment
- 🔄 **Container Support** - Docker and Kubernetes ready
- 🔄 **Cloud Integration** - AWS, Azure, and Google Cloud support

## 🤝 **Contributing**

We welcome contributions from the community! Whether you're fixing bugs, adding features, or improving documentation, your help is appreciated.

### **How to Contribute**

1. **Fork the Repository**
   ```bash
   git clone https://github.com/YOUR-USERNAME/Multi-Content-Management-System.git
   ```

2. **Create a Branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make Your Changes**
   - Write clean, documented code
   - Follow existing code style
   - Test your changes thoroughly
   - Update documentation if needed

4. **Commit Your Changes**
   ```bash
   git add .
   git commit -m "Add: Brief description of your changes"
   ```

5. **Push to Your Fork**
   ```bash
   git push origin feature/your-feature-name
   ```

6. **Open a Pull Request**
   - Go to the original repository
   - Click "New Pull Request"
   - Select your branch
   - Describe your changes clearly

### **Contribution Guidelines**

- 🐛 **Bug Fixes** - Always welcome! Include steps to reproduce
- ✨ **New Features** - Open an issue first to discuss
- 📖 **Documentation** - Improvements are greatly appreciated
- 🎨 **Themes** - Submit new themes and templates
- 🔌 **Modules** - Create and share custom modules
- 🌍 **Translations** - Help translate the CMS

### **Code Style**

- Use PSR-12 coding standards
- Add comments for complex logic
- Write meaningful commit messages
- Test on PHP 7.4+ and PHP 8.0+

### **Reporting Bugs**

Found a bug? Please open an issue with:
- Clear description of the problem
- Steps to reproduce
- Expected vs actual behavior
- PHP version and environment details
- Screenshots if applicable

### **Feature Requests**

Have an idea? Open an issue with:
- Clear description of the feature
- Use cases and benefits
- Possible implementation approach

## 🙏 **Acknowledgments**

This project wouldn't be possible without:

- **Contributors** - Thank you to everyone who has contributed code, documentation, and feedback
- **Inspiration** - WordPress, Joomla, and Magento for CMS architecture inspiration
- **Open Source Libraries** - jQuery, Bootstrap, Font Awesome, and many others
- **PHP Community** - For excellent documentation and support
- **Users** - Thank you for using and supporting this project

### **Special Thanks To:**
- All contributors and developers who have helped improve this project
- The open source community for tools and libraries
- Hosting providers who support PHP development
- Everyone who has reported bugs and suggested improvements

## 🔗 **Useful Links**

- **Repository**: [GitHub](https://github.com/Digitalcloud-Ali/Multi-Content-Management-System)
- **Live Demo**: [multicms.digitalcloud.no](http://multicms.digitalcloud.no)
- **Documentation**: [/docs folder](docs/)
- **Issues**: [Report a Bug](https://github.com/Digitalcloud-Ali/Multi-Content-Management-System/issues)
- **Pull Requests**: [Contribute](https://github.com/Digitalcloud-Ali/Multi-Content-Management-System/pulls)

---

## 📊 **Project Stats**

- **Current Version**: 2.0 (Major Modernization)
- **PHP Compatibility**: 7.4 - 8.3+
- **Active Development**: Yes
- **Last Major Update**: December 2024
- **Module Count**: 10+ built-in modules
- **Theme Support**: Yes (customizable)

---

**Built with ❤️ for the developer community**

*If you find this project useful, please consider giving it a ⭐ on GitHub!*
