
Setup the project initially.

```bash
composer config --append allow-plugins.singularo/shepherd-drupal-scaffold true
composer config --append --json extra.drupal-scaffold.allowed-packages '["singularo/shepherd-drupal-scaffold"]'
composer require singularo/shepherd-drupal-scaffold:dev-develop
```

The site can now be brought up with:
```bash
dsh
```

But we need other modules:
```bash
composer require drupal/token
```
