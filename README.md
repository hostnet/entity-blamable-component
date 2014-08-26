README
======


What is the Entity Blamable Component?
--------------------------------------
The Entity Blamable Component is a library that utilizes the [Entity Tracker Component](https://github.com/hostnet/entity-tracker-component/) and lets you hook in to the entityChanged event.

This component lets you automatically update entities by implementing `setUpdatedAt()`, `setUpdatedBy` and `setCreatedAt()`. At the given time, you have to implement all of them. If you need only 1 or 2 of the methods, pull requests are always allowed. It's not implemented yet due to lack of requirements in our use-cases.

Requirements
------------
The blamable component requires a minimal php version of 5.4 and runs on Doctrine2. For specific requirements, please check [composer.json](../master/composer.json).

Installation
------------

Installing is pretty easy, this package is available on [packagist](https://packagist.org/packages/hostnet/entity-blamable-component). You can register the package locked to a major as we follow [Semantic Versioning 2.0.0](http://semver.org/).

#### Example

```javascript
    "require" : {
        "hostnet/entity-blamable-component" : "0.*"
    }

```
> Note: You can use dev-master if you want the latest changes, but this is not recommended for production code!


Documentation
=============

How does it work?
-----------------

It works by putting the `@Blamable` annotaton and BlamableInterface on your entity and registering the listener on the entityChanged event, assuming you have already configured the [Entity Tracker Component](https://github.com/hostnet/entity-tracker-component/#setup).

For a usage example, follow the setup below.

Setup
-----

 - You have to add `@Blamable` to your entity
 - You have to add the BlamableInterface to your entity
 - You have to implement the BlamableProviderInterface on an object and pass it to the listener


#### Registering the events

Here's an example of a very basic setup. Setting this up will be a lot easier if you use a framework that has a Dependency Injection Container.

It might look a bit complicated to set up, but it's pretty much setting up the tracker component for the most part. If you use it in a framework, it's recommended to create a framework specific configuration package for this to automate this away.

```php

use Acme\Bundle\AcmeBundle\Service\AcmeBlamableProvider;
use Hostnet\Component\EntityBlamable\Listener\BlamableListener;
use Hostnet\Component\EntityBlamable\Resolver\BlamableResolver;
use Hostnet\Component\EntityTracker\Listener\EntityChangedListener;
use Hostnet\Component\EntityTracker\Provider\EntityAnnotationMetadataProvider;
use Hostnet\Component\EntityTracker\Provider\EntityMutationMetadataProvider;

/* @var $em \Doctrine\ORM\EntityManager */
$event_manager = $em->getEventManager();

// default doctrine annotation reader
$annotation_reader = new AnnotationReader();

// setup required providers
$mutation_metadata_provider   = new EntityMutationMetadataProvider($annotation_reader);
$annotation_metadata_provider = new EntityAnnotationMetadataProvider($annotation_reader);

// pre flush event listener that uses the @Tracked/@Blamable annotation
$entity_changed_listener = new EntityChangedListener(
    $mutation_metadata_provider,
    $annotation_metadata_provider
);

// the resolver is used to find the correct annotation
$blamable_resolver = new BlamableResolver($annotation_metadata_provider);

// the object that will provide the username and date time. This is an 
// application specific implementation of the BlamableProviderInterface
$blamable_provider = new AcmeBlamableProvider();

// creating the blamable listener
$blamable_listener = new BlamableListener($blamable_resolver, $blamable_provider);

// register the events
$event_manager->addEventListener('preFlush', $entity_changed_listener);
$event_manager->addEventListener('entityChanged', $blamable_listener);

```

#### Creating the Provider for the username and time
The provider is used to feed the required values to the BlamableListener, this is the only interface that requires a custom implementation in your project.

> Note that in the example I have defined the username as constructor param, but you might want to inject something that contains the currently logged in user and get that identifier.

```php

namespace Acme\Bundle\AcmeBundle\Service;

use Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface;

class AcmeBlamableProvider implements BlamableProviderInterface
{
    private $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function getUpdatedBy()
    {
        return $this->username;
    }
    
    public function getChangedAt()
    {
        return new \DateTime();
    }
}


```

#### Configuring the Entity
All we have to do now is put the @Blamable annotation and BlamableInterface on our Entity.

```php

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\EntityBlamable\Blamable;
use Hostnet\Component\EntityBlamable\BlamableInterface;

/**
 * @ORM\Entity
 * @Blamable
 */
class MyEntity implements BlamableInterface
{
    /**
     * @ORM\...
     */
    private $updated_by;
    
    /**
     * @ORM\...
     */
    private $updated_at;
    
    /**
     * @ORM\...
     */
    private $created_at;
    
    public function setUpdatedBy($by)
    {
        $this->updated_by = $by;
    }

    public function setUpdatedAt(\DateTime $at)
    {
        $this->changed_at = $at;
    }
    
    public function setCreatedAt(\DateTime $at)
    {
        $this->created_at = $at
    }
}

```

### What's next?

```php

$entity->setName('henk');
$em->flush();
// Voila, your updated_at and updated_by are filled in (and if it's new, created_at too).

```
