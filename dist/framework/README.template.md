WordPress Plugin Framework
===

By: Mike Flynn

Install
---
1. Clone this project into a folder in your project named framework
1. In the framework folder, run composer install
1. Include framework/load.php into your main plugin file
1. Create a class that extends `PluginFramework\Core` that has `$this->start('Plugin Name', 'version', __FILE__)` in the constructor
1. See the examples for more details
1. Profit!