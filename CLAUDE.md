# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A reference/practice project demonstrating **CQRS** and **Event-Driven Development** on **Symfony 6.2** (PHP 8.1+) using **Symfony Messenger** as the backbone for three separate buses (Command / Query / Event). It models a small store domain split into five **bounded contexts**.

## Commands

Everything runs inside the `cqe_php_817` Docker container. The `Makefile` wraps the common workflows (it uses `composer.phar`, the committed Composer binary, not a system `composer`):

```bash
make start          # docker compose up (php, nginx, mysql, redis, rabbitmq, redisinsight)
make install        # composer install + npm install (inside container)
make create-db      # drop + create + schema:create (dev DB)
make create-db-test # same for the test DB (APP_ENV=test)
make test           # runs check-style (phpcs PSR12) THEN phpunit
make stop
```

Quality tools (each runs in the container):

```bash
make lint             # ecs check          (Easy Coding Standard — only NoUnusedImportsFixer is enabled)
make static-analysis  # psalm
make test-architecture# phpstan (level 6, + phpat architecture rules via extension)
make mess-detector    # phpmd src,tests with phpmd.xml
```

Run a single test (exec into / target the container directly):

```bash
docker exec -w /var/www/html/ -t cqe_php_817 ./vendor/bin/simple-phpunit --filter EmailAddressTest
docker exec -w /var/www/html/ -t cqe_php_817 ./vendor/bin/simple-phpunit tests/Shared/Domain/ValueObject/EmailAddressTest.php
```

`composer` scripts: `check-style` (phpcs), `fix-style` (phpcbf), `run-unit-tests` (simple-phpunit).

### Driving the application

The app is exercised through console commands (there is no write API yet). After a command runs, **start the consumers** to process async events:

```bash
bin/console finance:add-product PR01 Bananas 5 12   # add to catalog
bin/console finance:purchase-product PR01 7         # finance buys -> warehouse
bin/console store:purchase-product PR01 4           # store sells

# consume the async work (events fan out across contexts via these)
bin/console messenger:consume async --queues=queue_async_urgent --queues=queue_async_normal
bin/console messenger:consume async_low
```

The web UI (read models / dashboards) is at http://localhost/. RabbitMQ management: http://localhost:15672 (rabbitmq/rabbitmq). RedisInsight: http://localhost:8001.

## Architecture

### The three buses (`src/Event`)

`src/Event` is the **infrastructure kernel** of the whole project — the CQRS/EDD machinery that every other context depends on. It is itself split Domain / Infrastructure:

- **Domain** (`src/Event/Domain/Bus`) defines framework-agnostic contracts: `Command`/`CommandBus`/`CommandHandler`, `Query`/`QueryBus`/`QueryHandler`, `Event`/`EventBus`/`EventHandler`, plus `EventOptions`, `EventConfiguration`, and `*NotRegisteredError` exceptions. All Commands/Queries/Events extend the base `Request`.
- **Infrastructure** (`src/Event/Infrastructure/Messenger`) adapts those contracts onto Symfony Messenger: `SymfonyCommandBus`, `SymfonyQueryBus`, `SymfonyEventBus`. These wrap `MessageBusInterface` and translate Messenger exceptions back into the domain `*NotRegisteredError` types.

Handlers are wired **automatically by interface**, not by config-per-handler. `config/services.yaml` uses `_instanceof` to tag every `CommandHandler` → `command.bus`, every `QueryHandler` → `query.bus`, every `EventHandler` → `event.bus`. So **a new handler just needs to implement the right interface and have an `__invoke()` typehinted on its message** — no registration step.

The three Messenger buses (`command.bus` / `query.bus` / `event.bus`) and their middleware are defined in `config/packages/messenger.yaml`. `command.bus` is the `default_bus` and adds `validation`, a `MessageLoggerMiddleware`, `dispatch_after_current_bus`, and `doctrine_transaction` (so a command handler's DB work + dispatched events are transactional).

### Event routing & configuration

Events are **asynchronous by default**. `config/packages/messenger.yaml` `routing` sends anything implementing `App\Event\Domain\Bus\Event\Event` to the `async` transport, and `App\Data\Domain\Event\LogBook` to the lower-priority `async_low`. Symfony mailer/notifier messages are isolated on the `notifications` (`sync://`) transport because the JSON serializer can't handle `SendEmailMessage`.

Per-event overrides go through `EventBus::notify($event, EventOptions::fromData([...]))`. `SymfonyEventConfiguration` translates `EventOptions` keys into Messenger stamps:
- `routing` (string) → `AmqpStamp` (selects an AMQP binding key / queue)
- `transport` (array) → `TransportNamesStamp` (force a specific transport)
- `delay` (int ms) → `DelayStamp`

Events are serialized as **JSON** (`messenger.yaml` serializer config) so the stored/transported representation is language-agnostic.

### Bounded contexts (`src/<Context>`)

Five contexts, each a vertical slice with the same `Domain / Application / Infrastructure` layering:

- **Finance** (business) — product catalog, purchasing, `AccountBook`.
- **Warehouse** (business) — stock, requests from the store.
- **Store** (business) — selling.
- **Communication** (technical) — sends emails on certain events (e.g. finance purchases).
- **Data** (technical) — a `LogBook` audit/BI log. `Data/Application/CaptureEvents/AllEventHandler` subscribes to the base `Event` type and re-emits every event (except `LogBook` itself) as a `LogBook` event — this is the global cross-context event sink.

Each context has its own `Product` entity by design — same name, different concept per context. Don't try to unify them.

Inside a context:
- `Domain/` — Entities, `Repository` interfaces, `Event/` definitions, `ValueObject/`. Doctrine mappings are **XML, not annotations** (`Infrastructure/Persistence/Doctrine/mapping/*.orm.xml` and `mappingVO/` for embedded value objects).
- `Application/<Aggregate>/<UseCase>/` — one folder per use case, each holding a `*Command`/`*Query`/`*Event` message and its `*Handler`. This is the canonical place to add behavior.
- `Infrastructure/` — `UI/Command` (console entrypoints), `UI/Controller` (read-side web), `Persistence/Doctrine/Doctrine*Repository` implementing the Domain repository interface.

### Typical flow

Console command or controller → `CommandBus::dispatch(SomeCommand)` → `SomeCommandHandler` mutates an entity via a repository and calls `EventBus::notify(SomeEvent)` → event is dispatched **after the current bus / transaction** and routed async → handlers in *other* contexts react (update their own read models, send mail, log to `LogBook`). Queries go through `QueryBus` and return a `Response`.

### Shared kernel (`src/Shared`)

Cross-cutting building blocks reused by all contexts: a `Criteria` query-object system (`Filter(s)`, `Order`, converted to Doctrine by `DoctrineCriteriaConverter`), the `AggregateRoot` base, base `ValueObject` types (`Uuid`, `EmailAddress`, `*ValueObject`, `Enum`), `Sluggable`/`Timestampable` traits, the `DoctrineRepository` base class (`persistAndFlush` / `removeAndFlush` / `repository()`), and a custom `UuidType`/`UuidGenerator` for Doctrine.

## Conventions when adding code

- A new use case = a new folder under `src/<Context>/Application/<Aggregate>/<UseCase>/` containing the message + handler. The handler implements `CommandHandler` / `QueryHandler` / `EventHandler` — that interface is all the wiring it needs.
- New entities: define a `Domain/Repository` interface, implement it as `Infrastructure/Persistence/Doctrine/Doctrine*Repository extends DoctrineRepository`, and add an XML mapping. Entity and ValueObject folders are **excluded from phpstan and psalm** (see `phpstan.neon`), so analysis focuses on application/domain logic.
- Keep domain code free of Symfony — adapt to the framework only in `Infrastructure`. `phpat` architecture rules are enforced via phpstan.
