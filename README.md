### Install
Run `git clone https://github.com/lexaboss/f4u-test-assignment.git .`   
Run composer install

### Usage
Database contains 3 clients with IDs:
- `e7a2d6bc-7643-11ea-bc55-0242ac130003`
- `e7a2d91e-7643-11ea-bc55-0242ac130003`
- `e7a2da18-7643-11ea-bc55-0242ac130003`

To get client shipping addresses list run:   
`make get <client-id>`   

To add shipping address to client run:   
`make add <client-id>`   

To update client shipping address run:   
`make update <client-id>`   

To remove client shipping address run:   
`make remove <client-id>`   

To run PHPUnit test:   
`make test`
   
Run for help:   
`make help`   


### User story
Let's say, in our system we have two models "client" and "shipping address". Let's assume that we already have some existing (registered) clients in our storage. Let's do this simple and assume that our clients have only three properties ID, firstname and lastname.

Client can have several different shipping addresses, but max number is 3. One of them is a default address, so when client adds the first address, it becomes default. Client can change a default address any time.

Client can add a new address, modify an existing address or remove an existing address. Client can not remove a default address, thus there should be at least one address (default) if it was added earlier.

Shipping address includes country, city, zipcode, street.

Implement a console application to be able to add, update, delete and get shipping addresses for a specific client.

Requirements: 
- 	Use PHP 7.*
- 	Use DDD ([Domain-Driven Design](https://www.amazon.com/exec/obidos/ASIN/0321125215/domainlanguag-20 "Domain-Driven Design"), [Domain-Driven Design in PHP](https://leanpub.com/ddd-in-php "Domain-Driven Design in PHP"))
- 	Use any storage you want for storing data, e.g. JSON files. ACID is not important here.
- 	Cover an application service layer by unit tests. If you need use e.g. PHPUnit. There is no need to cover all methods, just a couple to show the principle.
- Use plain PHP (no frameworks).

Fork your own copy of eglobal-it/f4u-test-assignment and share the result with us.

