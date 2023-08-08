# Affiliate Locator Service
The Irish Lottery Draw Date Calculator is a PHP application that calculates the next valid draw date for the Irish Lottery based on the input date and time.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Test Cases](#test-cases)

## Introduction

The Affiliate Locator Service is a versatile tool designed to efficiently match affiliates based on GPS coordinates within a specified range. This service is particularly useful for businesses that need to identify affiliates located near a specific geographic point, such as a company's office or event venue. By leveraging the Haversine formula, the service accurately calculates distances and provides a list of matching affiliates within a defined radius.

## Features

- **Efficient Geolocation Matching**: The service can swiftly process a list of affiliates and identify those within a specified distance of a reference point.

- **Flexible Configuration**: Configure the reference point's latitude and longitude to suit your use case. You can easily adapt the service to different locations.

- **Radius Limit**: The service allows you to set a distance limit to define the radius within which affiliates are considered matching.

- **Haversine Formula**: Accurate distance calculations are performed using the Haversine formula, which accounts for the curvature of the Earth's surface.

- **Exception Handling**: The service handles various exceptions, such as invalid JSON data and missing required fields, providing reliable and robust functionality.

- **Logging**: The service logs exceptions and errors encountered during processing, facilitating debugging and issue resolution.

- **Pagination**: The list of matching affiliates is paginated, making it easy to display results in manageable chunks.

## Requirements

- PHP 7.2 or higher
- Laravel Framework (if integrating with a Laravel project)
- Composer (for dependency management)

## Installation

1. download zip file and setup into your local machine:

2. Navigate to the project directory:

3. Install the required dependencies using Composer:Composer install

## Usage

1. Initialize the `AffiliateService` class.
2. Provide the file path to the list of affiliates in JSON format.
3. Specify the reference point's latitude and longitude.
4. Configure the radius for matching affiliates.
5. Call the `getMatchingAffiliates` method to obtain a paginated list of matching affiliates.

## Test Cases

The application includes test cases for the Affiliate Locator Service class using PHPUnit. To run the tests, execute the following command in the terminal:php artisan test
