[![Build Status](https://travis-ci.org/NYPL/hold-request-result-consumer.svg?branch=master)](https://travis-ci.org/NYPL/hold-request-result-consumer)
[![Coverage Status](https://coveralls.io/repos/github/NYPL/hold-request-result-consumer/badge.svg?branch=master)](https://coveralls.io/github/NYPL/hold-request-result-consumer?branch=master)

# NYPL Hold Request Result Consumer

This app listens on the HoldRequestResult streams and sends email notifications for hold success, failure, and EDD.

For more information, see this repo's [wiki](https://github.com/NYPL/hold-request-result-consumer/wiki)

It uses the
[NYPL PHP Microservice Starter](https://github.com/NYPL/php-microservice-starter).

This package adheres to [PSR-1](http://www.php-fig.org/psr/psr-1/),
[PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/)
(using the [Composer](https://getcomposer.org/) autoloader).

## Requirements

* Node 12
* PHP >=7.0
  * [pdo_pdgsql](http://php.net/manual/en/ref.pdo-pgsql.php)

This app depends on php 7.1. Check your php version:
```
php --version
```

If you need to install php 7.1 on OSX, this may work for you:
```
brew tap exolnet/homebrew-deprecated
brew install php@7.1
```

## Installation

1. Clone the repo.
2. Install required dependencies.
   * Run `npm install` to install Node.js packages.
   * Run `composer install` to install PHP packages.

## Configuration

./config/var_*environment* configures environment variables specific to each environment.

## Usage

### Process a Lambda Event

To use `node-lambda` to process the sample event(s), run:

~~~~
npm run test-event
~~~~

HoldRequestResultConsumer handles two kinds of events:
- Hold requests from Discovery UI that have errors
- Edd requests from Discovery UI

There is a sample edd request in the `events` folder. This edd request has already
been processed, so it won't result in an email send. To get a successful email send,
you must send a new event through the HoldRequestService (otherwise the HoldRequestResultConsumer won't be able to find the hold). See the GitHub repo for the HoldRequestService (https://github.com/NYPL/hold-request-service) for more information on how the different components of the hold request architecture relate.

The HoldRequestResult stream can also receive events from the RecapHoldRequestConsumer. In this case HoldRequestResultConsumer will send an email as well as a PATCH request to the HoldRequestService. For more information on recap events, see:

 * [Diagram of NYPL Request architecture](https://docs.google.com/presentation/d/1Tmb53yOUett1TLclwkUWa-14EOG9dujAyMdLzXOdOVc/edit#slide=id.g330b256cdf_0_0)
 * [Detailed description of hold request scenarios with reference to above diagram](https://docs.google.com/document/d/1AMqdUlKn5gV6o98JXfD2SjbIUZm04aGKXtupnmvJUN8/edit#heading=h.br4pvk4ymn9s)

 Also useful:

 * [Flow diagram documenting how item & EDD manifest across NYPL & HTC systems](https://docs.google.com/presentation/d/1G9wCyRswefgu4IvN6pn8ntuSVxJ6eEwYDzsdexTfHS8/edit#slide=id.g2a59ba2c93_0_439)
 * [HTC API wiki](https://htcrecap.atlassian.net/wiki/spaces/RTG/pages/25438542/Request+Item)

## Deployment

Travis is configured to deploy to qa and production from namesake branches.

## Contributing

This repo uses the [Main-QA-Production Git Workflow](https://github.com/NYPL/engineering-general/blob/master/standards/git-workflow.md#main-qa-production)
