
## Introduction
***

This project is a practice for CQRS and Event Driven Development using some great tools.

## Requirements
***

- Docker
- Make (If you don't have make, you can execute the same commands inside the Makefile)

## Preparation
***

`make start`

`make install`

`make create-db`

## Test
***

`make test`

## CQRS + Event Driven Development
***

### Code in `src/Event`

In the folder `src/Event` you can find the definition (Domain) and implementation (Infrastructure) that we have used in this project to the programming paradigms CQRS and EDD.

In the Domain folder we have the three Buses; Command, Query and Event

There is enough examples in this project to see how to implement every type of Bus, so, I'm not to go deeper to explain the classes, but if you need some information you can <a href="http://www.antoniobuenosvinos.com/hablamos/">contact me</a>

In the Infrastructure folder we can find the implementation in Symfony of the interfaces of the Domain.

Besides that code you also need to understand the information on:

* services.yaml<br/>
Helps symfony messenger to attach every Command/Query/Event that extends those interfaces to the required bus.
```
  _instanceof:
  App\Event\Domain\Bus\Command\CommandHandler:
  tags:
  - { name: messenger.message_handler, bus: command.bus }

        App\Event\Domain\Bus\Query\QueryHandler:
            tags:
                - { name: messenger.message_handler, bus: query.bus }

        App\Event\Domain\Bus\Event\EventHandler:
            tags:
                - { name: messenger.message_handler, bus: event.bus }
```

* messenger.yaml<br/>
>  The real core of this project, is where all the configuration is done

`serializer`<br/>
Where you defined how symfony messenger is going to serialize and deserialize the Events (the only which is going to store).<br/>
We have chose json because is an "agnostic format" who can understand every application (php, go, java, c#).

`transports`<br/>
Is where you defined is a transport (the system who route the messages to a queue) is synchronous or asynchronous, where store asynchronous messages (redis, rabbitmq, ...), the policies of failures, ...

`routing`<br/>
You can/need to route some Command/Query/Event to a specific transport
```
routing:
    Symfony\Component\Mailer\Messenger\SendEmailMessage: notifications
    Symfony\Component\Notifier\Message\ChatMessage: notifications
    Symfony\Component\Notifier\Message\SmsMessage: notifications
    Symfony\Component\Notifier\Message\PushMessage: notifications
    App\Data\Domain\Event\LogBook: async_low
    App\Event\Domain\Bus\Event\Event: async
```
In our project we route:
- the symfony classes to an isolate transport (notifications).
- The Event `App\Data\Domain\Event\LogBook` to a transport (async_low) which we are going to give less priority
- All the Events who implements `App\Event\Domain\Bus\Event\Event` to the asynchronous transport (async)

`buses`<br/>
The definition and configuration of the different buses. In our case we have two specific middlewares:
- MessageLoggerMiddleware; to trace what the buses are doing
- DispatchEvents; to dispatch the Events store in the Aggregates (not use in this project)

> Of course all this information is better explained here: https://symfony.com/doc/current/messenger.html


### Code in `src/Shared`

Besides `src/Event` we have another folder `src/Shared` with another technical stuff that helps us to complete all the functional requirements in the application:
- pattern Criteria to search items
- some Value Objects
- traits for slugs and timestamps for the entities
- ...

### Configuration of the events

The configuration of the queues can be done with the file `messenger.yaml`

But in addition we have some classes that help us to "readdress" this configuration.

If you make a look to `EventBus`, `EventOptions` and `SymfonyEventConfiguration` you could see that we can configure the Events with three options:
- delay; A time of delay before the execution of the Event
- transport; a specific transport
- routing; referer to the binding_keys in the `messenger.yaml` file to choose a queue

## Application
***

> Our application is about a store who sell products which are provided from a warehouse and a Finance department who buy those products.<br/>
> We have 5 Bounded Context, 3 of business nature and 2 of technical nature.<br/>
> Let's begin with the technical ones:

### Communication

It's a simple Bounded Context who from some Events send communications, in our case for every purchase made from the finance department we are going to email the "administrator".

### Data

It could be our BI application, but in our case this Bounded Context has an Entity named LogBook which from all the Events in the system stores what is going on.

> Let's continue with the business Bounded Context, but first some highlights:<br/>
> As you will see we have an Entity named Product in all three Bounded Context. That's totally fine because each Entity even their similarity represents a different concept in each Bounded Context and you can evaluate that comparing which attributes have every Entity.

### Finance

The department in charge of catalog and buy the products,

We have two commands; one to add products to the system and another to buy products whenever we want (but, in fact, you don't need to execute that to see how the system works)
We have three Entities:
- Product; represent the product from the point of view of the Financial department; Name, code, prices.
- Request; every time the warehouse needs products we are going to manage here that request and control if we are delivered or not
- AccountBook; the registry of all the purchases and sells of our business

### Warehouse

The department in charge of storing the products purchased by Finance before sending them to the store
We have two Entities:
- Product; represent the product from the point of view of the Warehouse department; Code, stock.
- Request; every time the store needs products we are going to manage here that request and control if we are delivered or not

### Store

The department in charge of selling the products.
We have only one Entity:
- Product; represent the product from the point of view of the Store department; Code, stock, price.

## Execution
***

> There are three commands where you can manage the application. As a practice you can do these three actions using an API.

* Add a product to the catalog of the system
```
bin/console finance:add-product PR01 Bananas 5 12
bin/console finance:add-product PR02 Apples 7 22
bin/console finance:add-product PR03 Cookies 3 10
```

* Purchase new items for a product and send to the warehouse
```
bin/console finance:purchase-product PR01 7
bin/console finance:purchase-product PR02 10
bin/console finance:purchase-product PR03 12
```

* A sale on the store
```
bin/console store:purchase-product PR01 4
bin/console store:purchase-product PR01 4
bin/console store:purchase-product PR02 6
bin/console store:purchase-product PR01 4
bin/console store:purchase-product PR01 4
bin/console store:purchase-product PR03 8
bin/console store:purchase-product PR01 4
```

* Process of the queues
> Depending on how the project has been configured, there are several ways to launch the consumers.<br/>
> In the case of this project the best is the second

```
bin/console messenger:consume async --queues=queue_async_urgent --queues=queue_async_normal
bin/console messenger:consume async_low
```

```
bin/console messenger:consume async async_low
```

On http://localhost/ you can navigate through the information generated for you and the application

## Next Steps
***

- Add an implementation for Laravel
- Add the concept of Store to manage more than one
- Better control of Exceptions
- Add some logical to manage the prices
- Add some logical to manage the quantities to request
- API to manage the application

## References
***

- https://symfony.com/doc/current/messenger.html
- https://www.doctrine-project.org/
- https://www.tailwindawesome.com/resources/astrolus/demo
- https://tailwindcss.com/docs/guides/symfony
- https://www.rabbitmq.com
- https://redis.io
- https://www.jetbrains.com/help/phpstorm/zero-configuration-debugging-cli.html
