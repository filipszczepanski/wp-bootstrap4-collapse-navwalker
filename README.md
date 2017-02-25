# WP Collapse NavWalker Bootsrap 4
Custom WordPress Nav Walker for Collapse Navigation of Bootstrap 4

### Features
+   multiple depth
+   parent items no redirection, only open collapse

## Installation
Put wp_bootstrap4_collapse_walker.php file into your WordPress theme folder:
`./wp-content/your-theme/`

Add the following code to the your WordPress theme functions.php file /wp-content/your-theme/functions.php :

```php
<?php
  // Register Custom Navigation Walker
  require_once('wp_bootstrap4_collapse_walker.php');
?>
```

## Usage
Add Custom Walker to the `wp_nav_menu()` array
```php
<?php
  wp_nav_menu([
    ...
    'walker' => new bs4navwalker()
  ]);
?>
```
### Example
1.  Register some menu:
    ```php
    <?php
      register_nav_menu('primary', 'Main Navigation');
    ?>
    ```

2.  Using into Your Theme:
    ```php
    <nav class="navbar text-xs-right text-lg-center">
    	<button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#nav-main">
    	 &#9776;
    	</button>
    	<?php
        wp_nav_menu([
    	    'menu'            => 'primary',
    	    'theme_location'  => 'primary',
    	    'container'       => 'div',
    	    'container_id'    => 'nav-main',
    	    'container_class' => 'collapse navbar-toggleable-md ',
    	    'menu_id'         => 'nav-main',
    	    'menu_class'      => 'nav navbar-nav',
    	    'depth'           => 3,
    	    'fallback_cb'     => 'bs4navwalker::fallback',
    	    'walker'          => new bs4navwalker()
        ]);
      ?>
    </nav>
    ```
