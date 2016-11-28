# Example using Cherry Framework V
This is an example to how [Cherry Framework V](https://github.com/CherryFramework/cherry-framework) works (*framework sources should be included in the theme for proper operation*).

## How to use?
1. Copy `cherry-framework-example` directory to your theme root
2. Require example in `functions.php`:
```
require get_template_directory() . '/cherry-framework-example/cherry-framework-example.php';
```

If you want to see breadcrumbs in your site - add code below in template file (recommended in header.php):
```
your_prefix_site_breadcrumbs();
```
