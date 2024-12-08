## An introduction to building AI things in PHP
<sub>Magic ✨</sub>

### Prerequisites

- [git](https://git-scm.com/)
- [php 8.4](https://www.php.net/releases/8.4/en.php)
- A server (Pick one you like most)
  - [Build in php server](https://www.php.net/manual/en/features.commandline.webserver.php)
  - [Symfony cli](https://symfony.com/download)
  - [XAMP](https://www.apachefriends.org/)
  - ...

- [TogetherAI API Key](https://www.together.ai/)

### What's inside

- [Slim](https://www.slimframework.com/), a micro php web framework
- [Guzzle PSR-7](https://github.com/guzzle/psr7) message implementation
- [thephpleague/container PRS-11](https://github.com/thephpleague/container) DI Container
- [SF Forms](https://symfony.com/doc/current/components/form.html)
- [Twig templating](https://twig.symfony.com/)
- [guzzle HTTP client](https://docs.guzzlephp.org/en/stable/overview.html)
- [SLEEK File DB](https://sleekdb.github.io)

### Install and run

```bash
composer install

# Serve using the build in default php server
php -S localhost:8000 -t public/
```

### Contents

Some guidance on creating the project yourself.

- [Project structure](./docs/1_project_structure)
- [TogetherAI Client](./docs/2_together_ai_client)
- [World generation](./docs/3_world_generation)
- [Data storage](./docs/4_data_storage)
- [Game chat](./docs/5_main_game)
- [Moderation](./docs/6_moderation)
