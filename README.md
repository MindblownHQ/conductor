### Develop conductor locally
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
