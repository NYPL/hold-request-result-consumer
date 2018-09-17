[![Build Status](https://travis-ci.org/NYPL/hold-request-result-consumer.svg?branch=master)](https://travis-ci.org/NYPL/hold-request-result-consumer)
[![Coverage Status](https://coveralls.io/repos/github/NYPL/hold-request-result-consumer/badge.svg?branch=master)](https://coveralls.io/github/NYPL/hold-request-result-consumer?branch=master)

# NYPL Hold Request Result Consumer

This package is intended to be used as a Lambda-based Node.js/PHP Listener to listen to a Kinesis Stream.

It uses the
[NYPL PHP Microservice Starter](https://github.com/NYPL/php-microservice-starter).

This package adheres to [PSR-1](http://www.php-fig.org/psr/psr-1/),
[PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/)
(using the [Composer](https://getcomposer.org/) autoloader).

## Requirements

* Node.js 6.10.2
* PHP >=7.0
  * [pdo_pdgsql](http://php.net/manual/en/ref.pdo-pgsql.php)

Homebrew is highly recommended for PHP:
  * `brew install php71`
  * `brew install php71-pdo-pgsql`


## Installation

1. Clone the repo.
2. Install required dependencies.
   * Run `npm install` to install Node.js packages.
   * Run `composer install` to install PHP packages.

## Configuration

Various files are used to configure and deploy the Lambda.

### .env

`.env` is used *locally* for the following purpose(s):

1. By `node-lambda` for deploying to and configuring Lambda in *all* environments.
   * You should use this file to configure the common settings for the Lambda
   (e.g. timeout, role, etc.) and include AWS credentials to deploy the Lambda.

### package.json

Configures `npm run` commands for each environment for deployment and testing. Deployment commands may also set the proper AWS Lambda VPC and security group.

~~~~
"scripts": {
  "deploy-development": "./node_modules/.bin/node-lambda deploy -e development -f config/var_dev.env -S config/event_sources_dev.json -b subnet-f4fe56af -g sg-1d544067 --profile nypl-sandbox --role arn:aws:iam::224280085904:role/lambda_basic_execution",
  "deploy-qa": "./node_modules/.bin/node-lambda deploy -e qa -f config/var_qa.env -S config/event_sources_qa.json -b subnet-f35de0a9,subnet-21a3b244 -g sg-aa74f1db --profile nypl-digital-dev --role arn:aws:iam::946183545209:role/lambda-full-access",
  "deploy-production": "./node_modules/.bin/node-lambda deploy -e production -f config/var_production.env -S config/event_sources_production.json -g --profile nypl-digital-dev --role arn:aws:iam::946183545209:role/lambda-full-access"
  },
~~~~

### var_app

Configures environment variables common to *all* environments.

### var_*environment*

Configures environment variables specific to each environment.

### event_sources_*environment*

Configures Lambda event sources (triggers) specific to each environment.

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

The HoldRequestResult stream can also receive events from the RecapHoldRequestConsumer. In this case HoldRequestResultConsumer will send an email as well as a PATCH request to the HoldRequestService. For more information on recap events, see the GitHub repo for the RecapHoldRequestService https://github.com/NYPL/recap-hold-request-service.

## Deployment

Travis is configured to deploy to development, qa, or production.
To deploy to the [development|qa|production] environment by hand, run the corresponding command:

~~~~
npm run deploy-[development|qa|production]
~~~~

## For more information
Please see this repo's [Wiki](https://github.com/NYPL/hold-request-result-consumer/wiki)
