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
   * If you have not already installed `node-lambda` as a global package, run `npm install -g node-lambda`.
3. Setup [configuration](#configuration) files.
   * Copy the `.env.sample` file to `.env`.
   * Copy `config/var_qa.env.sample` to `config/var_qa.env` and `config/var_production.env.sample` to `config/var_production.env`.

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
    "deploy-dev": "./node_modules/.bin/node-lambda deploy -e development -f config/var_dev.env -S config/event_sources_dev.json -b subnet-f4fe56af -g sg-1d544067 --profile nypl-sandbox --role arn:aws:iam::224280085904:role/lambda_basic_execution",
    "deploy-qa": "./node_modules/.bin/node-lambda deploy -e qa -f config/var_qa.env -S config/event_sources_qa.json -b subnet-f4fe56af -g sg-1d544067 --profile nypl-sandbox --role arn:aws:iam::224280085904:role/lambda_basic_execution",
    "deploy-production": "./node_modules/.bin/node-lambda deploy -e production -f config/var_production.env -S config/event_sources_production.json -g sg-116eeb60 --profile nypl-digital-dev --role arn:aws:iam::946183545209:role/lambda-full-access",
    "test-event": "./node_modules/.bin/node-lambda run -f config/var_app -j events/kinesis_hold_edd_success.json -x events/context.json"
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

## Deployment

Deployments (AWS account `nypl-digital-dev`):
 * Production: Lambda > Functions > HoldRequestResultConsumer-production
 * QA: Lambda > Functions > HoldRequestResultConsumer-qa

To deploy to the QA or Production environment, run the corresponding command:

~~~~
npm run deploy-qa
~~~~

or

~~~~
npm run deploy-production
~~~~

## For more information
Please see this repo's [Wiki](https://github.com/NYPL/hold-request-result-consumer/wiki)
