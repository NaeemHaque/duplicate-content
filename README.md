# WP Duplicate

A powerful WordPress plugin that allows you to duplicate posts, pages, categories, tags, custom post types, and other taxonomies with just a single click.

## 🚀 Features

- **One-Click Duplication**: Duplicate any content type with a single click
- **Multiple Content Types**: Support for posts, pages, categories, tags, and custom post types
- **Taxonomy Support**: Duplicate custom taxonomies and their terms
- **Clean Interface**: Simple and intuitive WordPress admin interface
- **Developer Friendly**: Well-structured code with proper namespacing
- **Internationalization Ready**: Full translation support

## 📋 Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

## 🛠️ Installation

### Method 1: Upload via WordPress Admin
1. Download the plugin ZIP file
2. Go to **WordPress Admin → Plugins → Add New**
3. Click **Upload Plugin**
4. Choose the ZIP file and click **Install Now**
5. Activate the plugin

### Method 2: Manual Installation
1. Download and extract the plugin files
2. Upload the `wp-duplicate` folder to `/wp-content/plugins/`
3. Go to **WordPress Admin → Plugins**
4. Find "WP Duplicate" and click **Activate**

## 🎯 Usage

After activation, you'll find a new menu item "WP Duplicate" in your WordPress admin sidebar with three submenus:

### Dashboard
- Main overview of the plugin
- Quick access to duplication features
- Status and statistics

### Settings
- Configure duplication options
- Set default behaviors
- Customize plugin preferences

### Help
- Documentation and guides
- FAQ section
- Support information

## 📁 Plugin Structure

```
wp-duplicate/
├── wp-dubplicate.php              # Main plugin file
├── index.php                      # Security file
├── uninstall.php                  # Cleanup on uninstall
├── LICENSE.txt                    # License information
├── README.md                      # This file
├── readme.txt                     # WordPress.org readme
├── assets/                        # Frontend assets
│   ├── css/
│   │   └── admin.css             # Admin styles
│   └── js/
│       └── admin.js              # Admin scripts
├── languages/                     # Translation files
│   └── wp-duplicate.pot          # Translation template
└── src/                          # Source code (MVC structure)
    ├── Plugin.php                # Main plugin class (Singleton)
    ├── Controllers/              # Controller classes
    │   └── AdminController.php   # Admin menu and functionality
    ├── Models/                   # Data models (future use)
    ├── Services/                 # Business logic services (future use)
    ├── Views/                    # Template files
    │   ├── dashboard.php         # Dashboard page template
    │   ├── settings.php          # Settings page template
    │   └── help.php              # Help page template
    └── Helpers/                  # Helper classes
        └── I18n.php              # Internationalization helper
```

## 🏗️ Architecture

The plugin follows a **Model-View-Controller (MVC)** pattern with proper namespacing:

### Core Components

#### Plugin.php
- **Singleton pattern** for main plugin class
- Handles initialization and hook registration
- Manages plugin lifecycle

#### AdminController.php
- **Menu creation** and admin interface
- **Asset management** (CSS/JS enqueuing)
- **Page rendering** for admin pages

#### Views/
- **Template files** for admin pages
- **Separation of concerns** between logic and presentation
- **Easy to customize** and extend

#### Helpers/
- **I18n.php**: Internationalization support
- **Reusable utilities** for common functionality

## 🔧 Development

### Adding New Features

1. **Controllers**: Add new controller classes in `src/Controllers/`
2. **Views**: Create template files in `src/Views/`
3. **Services**: Add business logic in `src/Services/`
4. **Models**: Create data models in `src/Models/`

### Code Standards

- **PSR-4** autoloading
- **WordPress coding standards**
- **Proper namespacing** (`WPDuplicate\`)
- **Documentation** for all public methods

### Hooks and Filters

The plugin provides various hooks for customization:

```php
// Example: Customize admin menu
add_filter('wp_duplicate_admin_menu_items', 'customize_menu_items');

// Example: Modify duplication behavior
add_action('wp_duplicate_before_duplicate', 'custom_before_duplicate');
add_action('wp_duplicate_after_duplicate', 'custom_after_duplicate');
```

## 🌐 Internationalization

The plugin is fully translatable:

- **Text Domain**: `wp-duplicate`
- **Translation Files**: Located in `/languages/`
- **Template**: `wp-duplicate.pot` for translators

### Adding Translations

1. Copy `languages/wp-duplicate.pot` to your language
2. Translate strings using Poedit or similar tool
3. Generate `.mo` file
4. Place in `languages/` directory

## 🐛 Troubleshooting

### Common Issues

**Q: Menu items don't appear**
A: Check if you have `manage_options` capability and the plugin is properly activated.

**Q: Styles not loading**
A: Verify that the `assets/css/admin.css` file exists and is readable.

**Q: JavaScript errors**
A: Check browser console for conflicts with other plugins.

### Debug Mode

Enable WordPress debug mode to see detailed error messages:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## 📝 Changelog

### Version 1.0.0
- Initial release
- Basic menu structure
- Admin interface setup
- Internationalization support
- MVC architecture implementation

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

### Development Setup

1. Clone the repository
2. Install dependencies (if any)
3. Set up local WordPress environment
4. Activate the plugin
5. Start developing!

## 📄 License

This project is licensed under the **GPL v2 or later** - see the [LICENSE.txt](LICENSE.txt) file for details.

## 👨‍💻 Authors

- **Golam Sarwer** - *Initial work* - [LinkedIn](https://www.linkedin.com/in/golam-sarwer-8626101a3/)

## 🙏 Acknowledgments

- WordPress community for the excellent platform
- Contributors and testers
- Open source community for inspiration

## 📞 Support

- **Documentation**: Check the Help section in the plugin
- **Issues**: Report bugs on GitHub
- **Feature Requests**: Submit via GitHub issues
- **Donations**: [Buy Me a Coffee](https://buymeacoffee.com/naeemhaque)

---

**Made with ❤️ for the WordPress community**
