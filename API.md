# API Documentation

This document provides details about the available API endpoints for managing customers and invoices in the Laravel Invoice and Customer API application.

## Base URL

The base URL for all API endpoints is:

```
http://domain-when-i-deploy/api/v1
```

## Authentication

All endpoints require authentication via Laravel Sanctum. You must include an API token in the `Authorization` header of your requests:

```
Authorization: Bearer YOUR_API_TOKEN
```

## API Token Management

API tokens are managed via the user dashboard (Blade UI):

Generate Token: Logged-in users can generate a personal access token from their profile settings.
Token Lifetime: The token is shown only once at generation and cannot be retrieved again.
Delete Token: Users can delete the token at any time from the same UI.
Regenerate Token: Deletes the old token and replaces it with a new one.

## Endpoints

### Customers

#### List All Customers

-   **Endpoint**: `/customers`
-   **Method**: GET
-   **Description**: Retrieve a list of all customers with optional filters.
-   **Filters**:
    -   `name`: Filter by customer name (e.g., `name[eq]=John`).
    -   `type`: Filter by customer type (e.g., `type[eq]=premium`).
    -   `email`: Filter by email address (e.g., `email[eq]=example@example.com`).
    -   `address`: Filter by address (e.g., `address[eq]=123 Main St`).
    -   `city`: Filter by city (e.g., `city[eq]=New York`).
    -   `state`: Filter by state (e.g., `state[eq]=NY`).
    -   `postalCode`: Filter by postal code with operators `eq`, `gt`, `lt` (e.g., `postalCode[gt]=10000`).
-   **Response**: Returns an array of customer objects.

```json
[
    {
        "id": 1,
        "name": "John Doe",
        "type": "I",
        "email": "john@example.com",
        "address": "123 Main St",
        "city": "New York",
        "state": "NY",
        "postal_code": "10001"
    }
]
```

#### Create a New Customer

-   **Endpoint**: `/customers`
-   **Method**: POST
-   **Description**: Create a new customer.
-   **Request Body**:
    -   `name` (string, required)
    -   `type` (string, required): Must be one of `I`, `B`, `i`, `b`.
    -   `email` (string, required): Must be a valid email format.
    -   `address` (string, required)
    -   `city` (string, required)
    -   `state` (string, required)
    -   `postal_code` (string, required)
-   **Response**: Returns the created customer object.

```json
{
    "name": "Jane Smith",
    "type": "B",
    "email": "jane@example.com",
    "address": "456 Market St",
    "city": "Chicago",
    "state": "IL",
    "postal_code": "60601"
}
```

#### Update a Customer

-   **Endpoint**: `/customers/{id}`
-   **Method**: PUT/PATCH
-   **Description**: Update an existing customer.
-   **Request Body**:
    -   Fields to be updated (e.g., `name`, `email`).
-   **Response**: Returns the updated customer object.

#### Delete a Customer

-   **Endpoint**: `/customers/{id}`
-   **Method**: DELETE
-   **Description**: Delete a customer by ID.
-   **Response**: Returns a success message or status.

```json
{ "message": "Customer deleted successfully." }
```

### Invoices

#### List All Invoices

-   **Endpoint**: `/invoices`
-   **Method**: GET
-   **Description**: Retrieve a list of all invoices with optional filters.
-   **Filters**:
    -   `customerId`: Filter by customer ID (e.g., `customerId[eq]=1`).
    -   `amount`: Filter by amount with operators `eq`, `gt`, `lt`, `gte`, `lte` (e.g., `amount[gt]=100`).
    -   `status`: Filter by status with operators `eq`, `ne` (e.g., `status[eq]=paid`).
    -   `billedDate`: Filter by billed date with operators `eq`, `gt`, `lt`, `gte`, `lte` (e.g., `billedDate[lt]=2023-01-01`).
    -   `paidDate`: Filter by paid date with operators `eq`, `gt`, `lt`, `gte`, `lte` (e.g., `paidDate[gte]=2023-01-01`).
-   **Response**: Returns an array of invoice objects.

#### Create a New Invoice

-   **Endpoint**: `/invoices`
-   **Method**: POST
-   **Description**: Create a new invoice.
-   **Request Body**:
    -   `customer_id` (integer, required)
    -   `amount` (numeric, required): Must be greater than or equal to 0.
    -   `status` (string, required): Must be one of `B`, `P`, `V`, `b`, `p`, `v`.
    -   `billed_date` (string, required): Must be in `Y-m-d H:i:s` format.
    -   `paid_date` (string, nullable): Must be in `Y-m-d H:i:s` format and after or equal to `billed_date`.

```json
{
    "customer_id": 1,
    "amount": 250.0,
    "status": "P",
    "billed_date": "2024-01-10 10:00:00",
    "paid_date": "2024-01-15 12:00:00"
}
```

-   **Response**: Returns the created invoice object.

#### Update an Invoice

-   **Endpoint**: `/invoices/{id}`
-   **Method**: PUT/PATCH
-   **Description**: Update an existing invoice.
-   **Request Body**:
    -   Fields to be updated (e.g., `amount`, `status`).
-   **Response**: Returns the updated invoice object.

#### Delete an Invoice

-   **Endpoint**: `/invoices/{id}`
-   **Method**: DELETE
-   **Description**: Delete an invoice by ID.
-   **Response**: Returns a success message or status.

```json
{ "message": "Invoice deleted successfully." }
```

#### Bulk Create Invoices

-   **Endpoint**: `/invoices/bulk`
-   **Method**: POST
-   **Description**: Create multiple invoices in a single request.
-   **Request Body**: An array of invoice objects with the following fields:
    -   `customer_id` (integer, required)
    -   `amount` (numeric, required): Must be greater than or equal to 0.
    -   `status` (string, required): Must be one of `B`, `P`, `V`, `b`, `p`, `v`.
    -   `billed_date` (string, required): Must be in `Y-m-d H:i:s` format.
    -   `paid_date` (string, nullable): Must be in `Y-m-d H:i:s` format and after or equal to `billed_date`.
-   **Response**: Returns an array of created invoice objects.

## Error Handling

All endpoints return standard HTTP status codes to indicate the success or failure of an API request. Common status codes include:

-   `200 OK`: The request was successful.
-   `201 Created`: The resource was successfully created.
-   `400 Bad Request`: The request was invalid or cannot be served.
-   `401 Unauthorized`: Authentication is required and has failed or has not yet been provided.
-   `404 Not Found`: The requested resource could not be found.
-   `500 Internal Server Error`: An error occurred on the server.
