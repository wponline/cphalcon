# [4.1.0](https://github.com/phalcon/cphalcon/releases/tag/v4.1.0) (xxx-xx-xx)
## Added
- Added JWT (JSON Web Tokens) support under `Phalcon\Security\JWT`. Offers support for:
  - Token
  - Parser
  - Builder
  - Validator
  - Signers (None, HMAC)
  - Base64 encode/decodeUrl helper class
[#13856](https://github.com/phalcon/cphalcon/issues/13856)
- Added additional HTML helpers under `Phalcon\Html\Helper`: `Anchor`, `Base`, `Body`, `Button`, `Close`, `Element`, `Form`, `Img`, `Input\Color`, `Input\Date`, `Input\DateTime`, `Input\DateTimeLocal`, `Input\Email`, `Input\File`, `Input\Hidden`, `Input\Image`, `Input\Input`, `Input\Month`, `Input\Numeric`, `Input\Password`, `Input\Range`, `Input\Select`, `Input\Search`, `Input\Submit`, `Input\Tel`, `Input\Text`, `Input\Textarea`, `Input\Time`, `Input\Url`, `Input\Week`, `Label`, `Link`, `Meta`, `Ol`, `Script`, `Style`, `Title`, `Ul`
[#14696](https://github.com/phalcon/cphalcon/issues/14696)
- Added `Phalcon\Http\Request::getPreferredIsoLocaleVariant()` to return the base language if this is a specific one (`en` vs `en-US`) [#3135](https://github.com/phalcon/cphalcon/issues/3135)
- Added `preload` for Volt, which will send a HTTP/2 preload header [#13128](https://github.com/phalcon/cphalcon/issues/13128)
- Added `Phalcon\Helper\Arr::blackList()` to exclude elements of an array by the keys obtained from the elements of a blacklist [#14801](https://github.com/phalcon/cphalcon/issues/14801) [@TimurFlush](https://github.com/TimurFlush)
- Added `Phalcon\Debug::renderHtml()` to get a HTML representation of the exception [#14794](https://github.com/phalcon/cphalcon/issues/14794) [@TimurFlush](https://github.com/TimurFlush)
- Added `Phalcon\Mvc\Router\Annotations->setActionPreformatCallback($callback)` to set a callback which pre-formats actions to custom pattern [#14819](https://github.com/phalcon/cphalcon/pull/14819)
- Added new PDO wrapper for the Data Mapper implementation, with decorated instance, locator and profiler
  - `Phalcon\DataMapper\Pdo\Connection`
  - `Phalcon\DataMapper\Pdo\Connection\Decorated`
  - `Phalcon\DataMapper\Pdo\Profiler\Profiler`
  - `Phalcon\DataMapper\Pdo\Profiler\MemoryLogger`
  - `Phalcon\DataMapper\Pdo\ConnectionLocator`
This component will be used in the Data Mapper implementation but can be used as a stand alone component for PDO connections. [#14733](https://github.com/phalcon/cphalcon/issues/14733)
- Added new Query Builder, as well as a factory, for the Data Mapper implementation supporting CRUD with bound parameters
  - `Phalcon\DataMapper\Query\Bind`
  - `Phalcon\DataMapper\Query\Delete`
  - `Phalcon\DataMapper\Query\Insert`
  - `Phalcon\DataMapper\Query\Select`
  - `Phalcon\DataMapper\Query\Update`
  - `Phalcon\DataMapper\Query\QueryFactory`
This component can be used to create SQL statements using a fluent interface. Optionally the statements can be executed from the builder itself using the `DataMapper\Pdo` connection. [#14734](https://github.com/phalcon/cphalcon/issues/14734)
- Added `Phalcon\Mvc\Micro\LazyLoader::getHandler()` to return real handler when using lazy loaded controllers for `Phalcon\Mvc\Micro` [#14871](https://github.com/phalcon/cphalcon/issues/14871) [@Jurigag](https://github.com/Jurigag)

## Changed
- Added service checks for the session. Now cookies will be saved in the session only when the `session` service is defined [#11770](https://github.com/phalcon/cphalcon/issues/11770), [#14649](https://github.com/phalcon/cphalcon/pull/14649)
- Changed `Phalcon\Db\Adapter\*::getRawSQLStatement()` to return the full SQL query with parameters [#12196](https://github.com/phalcon/cphalcon/issues/12196)
- Changed `Phalcon\Filter::sanitize` to throw a `E_USER_NOTICE` when a filter does not exist. [#14679](https://github.com/phalcon/cphalcon/issues/14679)
- PHQL now supports the use of any printable characters from the extended ASCII
  table for escaped identifiers. The exception characters are `[` and `]`. To
  use `[` and `]` escape they (`\[`, `\]`) [#14535](https://github.com/phalcon/cphalcon/issues/14535)

## Fixed
- Fixed `Phalcon\Db\Dialect\Mysql::getColumnDefinition` to recognize `size` for `DATETIME`, `TIME` and `TIMESTAMP` columns [#13297](https://github.com/phalcon/cphalcon/issues/13297)

## Removed
- Removed `Phalcon\Http\Cookie` binding to session [#11770](https://github.com/phalcon/cphalcon/issues/11770)
  - `Phalcon\Http\Cookie` no longer depends on the session service and data will not be duplicated in the session. This made it difficult to use cookies in stateless applications (SPA).

`
