terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 4.0"
    }
  }
}

provider "aws" {
  region     = "us-east-1"
}

variable "environment" {
  type = string
  default = "qa"
  description = "The name of the environnment (qa, production). This controls the name of lambda and the env vars loaded."

  validation {
    condition     = contains(["qa", "production"], var.environment)
    error_message = "The environment must be 'qa' or 'production'."
  }
}

# Package the app as a zip:
data "archive_file" "lambda_zip" {
  type        = "zip"
  output_path = "${path.module}/dist.zip"
  source_dir  = "../../"
  excludes    = [".git", ".terraform", "provisioning"]
}

# Upload the zipped app to S3:
resource "aws_s3_object" "uploaded_zip" {
  bucket = "nypl-travis-builds-${var.environment}"
  key    = "hold-request-result-consumer-${var.environment}-dist.zip"
  acl    = "private"
  source = data.archive_file.lambda_zip.output_path
  etag   = filemd5(data.archive_file.lambda_zip.output_path)
}

# Create the lambda:
resource "aws_lambda_function" "lambda_instance" {
  description   = "PHP Lambda processing Hold Request Results and send e-mail notificaitons."
  function_name = "HoldRequestResultConsumer-${var.environment}"
  handler       = "listener.handler"
  memory_size   = 256
  role          = "arn:aws:iam::946183545209:role/lambda-full-access"
  runtime       = "nodejs12.x"
  timeout       = 30
  layers        = ["arn:aws:lambda:us-east-1:946183545209:layer:lib-for-node10-wrapped-php7:1"]
  tags          = {
    Environment = var.environment
    Project     = "Catalog"
  }


  # Location of the zipped code in S3:
  s3_bucket     = aws_s3_object.uploaded_zip.bucket
  s3_key        = aws_s3_object.uploaded_zip.key

  # Trigger pulling code from S3 when the zip has changed:
  source_code_hash = data.archive_file.lambda_zip.output_base64sha256


  # Load ENV vars from ./config/{environment}.env
  environment {
    variables = { for tuple in regexall("(.*?)=(.*)", file("../../config/var_${var.environment}.env")) : tuple[0] => tuple[1] }
  }
}

# Have the lambda listen on the HoldRequestResult stream:
resource "aws_lambda_event_source_mapping" "kinesis_trigger" {
  function_name = "HoldRequestResultConsumer-${var.environment}"
  event_source_arn  = "arn:aws:kinesis:us-east-1:946183545209:stream/HoldRequestResult-${var.environment}"
  starting_position = "LATEST"
  batch_size        = 5
  enabled           = true
}
