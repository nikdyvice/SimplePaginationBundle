Simple Pagination Bundle
========================

[![Build Status](https://travis-ci.org/AshleyDawson/SimplePagination.svg?branch=develop)](https://travis-ci.org/AshleyDawson/SimplePagination)

Symfony 2 bundle for the [Simple Pagination](https://github.com/AshleyDawson/SimplePagination) library.

Installation
------------

You can install the Simple Pagination Bundle via [Composer](https://getcomposer.org/). To do that, simply `require` the 
package in your `composer.json` file like so:

```json
{
    "require": {
        "ashleydawson/simple-pagination-bundle": "1.0.*"
    }
}
```

Run `composer update` to install the package. Then you'll need to register the bundle in your `app/AppKernel.php`:

```php
$bundles = array(
    // ...
    new AshleyDawson\SimplePaginationBundle\AshleyDawsonSimplePaginationBundle(),
);
```

Basic Usage
-----------

The simplest collection we can use the paginator service on is an array. Please see below for an extremely
simple example of the paginator operating on an array. This shows the service paginating over an array of 
12 items.

```php
namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        // Get the paginator service from the container
        $paginator = $this->get('ashley_dawson_simple_pagination.paginator');

        // Build mock set of items to paginate over
        $items = array(
            'Banana',
            'Apple',
            'Cherry',
            'Lemon',
            'Pear',
            'Watermelon',
            'Orange',
            'Grapefruit',
            'Blackcurrant',
            'Dingleberry',
            'Snosberry',
            'Tomato',
        );

        // Set the item total callback, simply returning the total number of items
        $paginator->setItemTotalCallback(function () use ($items) {
            return count($items);
        });

        // Add the slice callback, simply slicing the items array using $offset and $length
        $paginator->setSliceCallback(function ($offset, $length) use ($items) {
            return array_slice($items, $offset, $length);
        });

        // Perform the pagination, passing the current page number from the request
        $pagination = $paginator->paginate((int)$this->get('request')->query->get('page', 1));

        // Pass the pagination object to the view for rendering
        return $this->render('AcmeDemoBundle:Welcome:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
}
```

And in the twig view, it looks like this:

```twig
...

{# Iterate over items for the current page, rendering each one #}
<ul>
    {% for item in pagination.items %}
        <li>{{ item }}</li>
    {% endfor %}
</ul>

{# Iterate over the page list, rendering the page links #}
<div>
    {% for page in pagination.pages %}
        <a href="?page={{ page }}">{{ page }}</a> |
    {% endfor %}
</div>

...
```

Please note that this is **a very simple example**, some advanced use-cases and interfaces are coming up (see below).

Configuration
-------------

You can configure the Simple Pager Bundle from `app/config/config.yml` with the following **optional** parameters:

```yaml
ashley_dawson_simple_pagination:
  defaults:
    items_per_page: 10
    pages_in_range: 5
```

Custom Service
--------------

If you'd like to define the paginator as a custom service, please use the following
service container configuration.

In YAML:

```yaml

services:

  my_paginator:
    class: AshleyDawson\SimplePagination\Paginator
    calls:
      - setItemsPerPage, [ 10 ]
      - setPagesInRange, [ 5 ]

```

or in XML:

```xml
<services>
    <service id="my_paginator" class="AshleyDawson\SimplePagination\Paginator">
        <call method="setItemsPerPage">
            <argument>10</argument>
        </call>
        <call method="setPagesInRange">
            <argument>5</argument>
        </call>
    </service>
</services>
```

Then use it in your controllers like this:

```php
// Get my paginator service from the container
$paginator = $this->get('my_paginator');

// ...
```