Michels
=======

Skin Michels

Build status:
[![Build Status](https://travis-ci.org/cbleek/Michels.svg?branch=master)](https://travis-ci.org/cbleek/michels)
[![Latest Stable Version](https://poser.pugx.org/cbleek/michels/v/stable)](https://packagist.org/packages/yawik/michels)
[![Total Downloads](https://poser.pugx.org/cbleek/michels/downloads)](https://packagist.org/packages/yawik/michels)

Installation
------------

you can download and use this skin by:

```sh
$ git clone https://github.com/cbleek/Michels.git MyPath
$ cd MyPath
$ composer install
```

The module comes with a sandbox. You can start the sandbox by:

```sh
$ composer serve
```

after that, you should be able to open http://localhost:8000

To activate the module, create a file in you `test/sandbox/config/autoload` directory

```
<?php
return ['Michels'];
```
