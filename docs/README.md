# Using a model
* There are two ways to use a model in a controller:

the first is to use controller's `loadModel()` method. Ex: 
```php 
    // @constructor
    $this->loadModel('SomeModel', 'AnotherModel');
    $this->model = new SomeModel();
    $this->model2 = new AnotherModel();
```
and using `useModel()` helper function. Ex:
```php
    // @constructor
    $this->model = useModel('SomeModel');
```