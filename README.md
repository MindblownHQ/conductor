# Local Development Notes

### Load composer package via symlink
For local development, you can set the repository as in the example below, so composer will always load the package with a symlink to the path where your git repo lives.

```json
    "repositories": [
      {
        "type": "path",
        "url": "~/Developer/MindBlownHQ/conductor",
        "options": {
            "symlink": true
      }
    }
```

When you run composer update, it should show:
`Installing shop-maestro/conductor (dev-main): Symlinking from /Users/marinusklasen/Developer/MindBlownHQ/conductor`

### Overwrite the wooping api endpoint for license verification
```php
add_filter( 'shop-maestro/conductor/api-url', function( $url ) {
	return 'https://wooping.test/wp-json/wooping/v1/';
});
```
