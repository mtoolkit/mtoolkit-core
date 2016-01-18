MToolkit - Core
===============
The core module of [MToolkit](https://github.com/mtoolkit/mtoolkit) framework.

#Summary
- [Install](#install)
- [Into](#intro)

#<a name="install"></a>Install
```json
{
    "require": {
        "mtoolkit/mtoolkit-core": "..."
    }
}
```
Or run the console command:
```
composer require mpstyle/mtoolkit-core
```

#<a name="intro"></a>Into
The core module contains some utility classes like: MList, MGet, MPost, MSession.

Theese wrap the core functionalities of PHP in a class. Let me explain.

## MList
It implements a list of object (custom or primitive) and provides the methods to work with them:
- append: adds a new item at the end of the list
- appendArray: adds all the item in the array argument at the end of the list
- clear: empty the list
- count: returns the number of the item
- isEmpty: removes all items from list
They are only some of the methods to work with a list of objects.

## MGet and MPost
As the names suggest, you can use theese classes to access to the globals $_GET and $_POST.
They are "read only" MMap, so it is impossible to call methods as: clear, erase and others.
To retrieve the value in the global $_GET write:
```php
$get=new MGet();
$id=$get->getValue('id');
```
In a similar way for $_POST:
```php
$post=new MPost();
$id=$post->getValue('id');
```
If 'id' is not set, it returns null.
