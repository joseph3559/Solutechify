# Event API Reference

This document describes the available API endpoints for managing events in Solutechify.

## Base URL

```
https://api.solutechify.com/v1
```

## Authentication

All endpoints require a valid JWT token in the Authorization header:

```
Authorization: Bearer <your_jwt_token>
```

## Endpoints

### List Events

```http
GET /events
```

Query parameters:
- `page` (integer, default: 1): Page number for pagination
- `limit` (integer, default: 10): Number of items per page
- `status` (string): Filter by event status (draft, published, completed)
- `organization_id` (string): Filter by organization
- `search` (string): Search events by title or description

Response:
```json
{
  "data": [
    {
      "id": "uuid",
      "title": "Event Title",
      "description": "Event Description",
      "date": "2024-03-20T18:00:00Z",
      "venue": "Event Venue",
      "price": 1000,
      "max_attendees": 100,
      "current_attendees": 45,
      "status": "published",
      "organization_id": "org_uuid",
      "created_at": "2024-01-01T12:00:00Z",
      "updated_at": "2024-01-01T12:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "total_pages": 5,
    "total_items": 48,
    "items_per_page": 10
  }
}
```

### Get Single Event

```http
GET /events/:id
```

Response:
```json
{
  "data": {
    "id": "uuid",
    "title": "Event Title",
    "description": "Event Description",
    "date": "2024-03-20T18:00:00Z",
    "venue": "Event Venue",
    "price": 1000,
    "max_attendees": 100,
    "current_attendees": 45,
    "status": "published",
    "organization_id": "org_uuid",
    "created_at": "2024-01-01T12:00:00Z",
    "updated_at": "2024-01-01T12:00:00Z"
  }
}
```

### Create Event

```http
POST /events
```

Request body:
```json
{
  "title": "Event Title",
  "description": "Event Description",
  "date": "2024-03-20T18:00:00Z",
  "venue": "Event Venue",
  "price": 1000,
  "max_attendees": 100,
  "status": "draft"
}
```

### Update Event

```http
PUT /events/:id
```

Request body:
```json
{
  "title": "Updated Event Title",
  "description": "Updated Description",
  "date": "2024-03-21T18:00:00Z",
  "venue": "Updated Venue",
  "price": 1500,
  "max_attendees": 150,
  "status": "published"
}
```

### Delete Event

```http
DELETE /events/:id
```

### Register for Event

```http
POST /events/:id/register
```

Request body:
```json
{
  "name": "Attendee Name",
  "email": "attendee@example.com",
  "phone": "+254712345678"
}
```

Response:
```json
{
  "data": {
    "registration_id": "uuid",
    "event_id": "event_uuid",
    "status": "confirmed",
    "ticket_number": "EVT-2024-001",
    "created_at": "2024-01-01T12:00:00Z"
  }
}
```

## Error Responses

```json
{
  "error": {
    "code": "ERROR_CODE",
    "message": "Error description",
    "details": {
      "field": ["Error detail"]
    }
  }
}
```

Common error codes:
- `INVALID_REQUEST`: Invalid request parameters
- `NOT_FOUND`: Event not found
- `UNAUTHORIZED`: Invalid or missing authentication
- `FORBIDDEN`: Insufficient permissions
- `EVENT_FULL`: Event has reached maximum attendees
- `VALIDATION_ERROR`: Invalid input data

## Rate Limiting

- 1000 requests per hour per API key
- Rate limit headers included in response:
  - `X-RateLimit-Limit`
  - `X-RateLimit-Remaining`
  - `X-RateLimit-Reset`

## Webhooks

Events can trigger webhooks for:
- Event created
- Event updated
- Event deleted
- New registration
- Registration cancelled

Configure webhooks in your organization settings. 