# Example using Cherry Framework V
This is an example to show how [Cherry Framework V](https://github.com/CherryFramework/cherry-framework) works.

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
