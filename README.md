
# Dump PHP

#### Display global names and backtrace.

#### Dump the global namespace.


## Usage

```php
    new Dump( [ 'all' | string method_name | array (method_names) ] [, boolean output_type] );
```


### Examples


```php
    require('Dump.class.php');

    new Dump();

    new Dump('displayFunctions', TRUE);

    new Dump( ['displayMemory', 'backtrace'] );
```
