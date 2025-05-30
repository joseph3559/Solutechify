# Multi-tenant Architecture

This document describes how Solutechify implements multi-tenancy to securely handle multiple organizations.

## Overview

Solutechify uses a multi-tenant architecture where multiple organizations can use the same application instance while maintaining data isolation and security. We implement a hybrid approach combining the benefits of both database-per-tenant and shared database models.

## Architecture Design

### Tenant Isolation

1. **Database Schema**
   - Shared database with tenant-specific schemas
   - Each organization gets its own PostgreSQL schema
   - System-wide tables remain in the public schema
   - Cross-tenant queries are prevented at the database level

2. **Request Flow**
   ```mermaid
   sequenceDiagram
     participant Client
     participant LoadBalancer
     participant TenantMiddleware
     participant AppServer
     participant Database
     
     Client->>LoadBalancer: Request with tenant identifier
     LoadBalancer->>TenantMiddleware: Route request
     TenantMiddleware->>TenantMiddleware: Resolve tenant
     TenantMiddleware->>AppServer: Set tenant context
     AppServer->>Database: Query with tenant schema
     Database->>AppServer: Return tenant data
     AppServer->>Client: Response
   ```

### Tenant Resolution

1. **Domain-based Resolution**
   - Each organization gets a subdomain: `{tenant}.solutechify.com`
   - Custom domains supported: `events.customersite.com`

2. **Path-based Resolution**
   - Alternative URL format: `solutechify.com/{tenant}/`
   - Used for specific features and API endpoints

## Implementation Details

### Database Management

1. **Schema Creation**
```sql
-- Create new tenant schema
CREATE SCHEMA tenant_{id};

-- Clone base schema structure
CALL clone_base_schema('tenant_{id}');

-- Set permissions
GRANT USAGE ON SCHEMA tenant_{id} TO app_user;
```

2. **Query Isolation**
```typescript
// Middleware to set tenant context
const setTenantContext = async (req, res, next) => {
  const tenant = await resolveTenant(req);
  req.tenantId = tenant.id;
  // Set current schema for connection
  await db.raw(`SET search_path TO tenant_${tenant.id}, public`);
  next();
};
```

### Caching Strategy

1. **Redis Cache Namespacing**
```typescript
const cacheKey = `tenant:${tenantId}:${resource}:${id}`;
```

2. **Tenant-specific Cache Invalidation**
```typescript
// Invalidate tenant-specific cache
await redis.del(`tenant:${tenantId}:*`);
```

### Asset Management

1. **File Storage**
   - Tenant-specific folders in cloud storage
   - Path format: `/{tenant_id}/uploads/...`
   - Separate CDN distributions per tenant (optional)

2. **Asset URLs**
```typescript
const assetUrl = `${cdnBase}/${tenantId}/${assetPath}`;
```

## Security Considerations

### Data Isolation

1. **Database Level**
   - Row-level security policies
   - Schema-level access control
   - Connection pooling per tenant

2. **Application Level**
   - Tenant context validation
   - Cross-tenant access prevention
   - Request scope isolation

### Authentication & Authorization

1. **JWT Tokens**
   - Tenant information embedded in tokens
   - Separate signing keys per tenant
   - Short expiration times

2. **Role-Based Access Control (RBAC)**
   - Tenant-specific roles
   - Global admin roles
   - Feature-based permissions

## Scaling Considerations

### Horizontal Scaling

1. **Database Sharding**
   - Shard by tenant ID
   - Separate database instances for high-traffic tenants
   - Read replicas per shard

2. **Application Scaling**
   - Stateless application servers
   - Tenant-aware load balancing
   - Regional deployments

### Resource Limits

1. **Tenant Quotas**
   - Storage limits
   - API rate limits
   - Concurrent user limits
   - Event count limits

2. **Monitoring**
   - Per-tenant metrics
   - Resource usage alerts
   - Performance monitoring

## Migration & Maintenance

### Tenant Migration

1. **Schema Updates**
```bash
# Apply migrations to all tenant schemas
for tenant in $(list_tenants); do
  npm run migrate --schema=tenant_$tenant
done
```

2. **Data Migration**
   - Tenant-specific data migration tools
   - Background job processing
   - Progress tracking

### Backup & Recovery

1. **Backup Strategy**
   - Full database backups
   - Per-tenant logical backups
   - Point-in-time recovery

2. **Disaster Recovery**
   - Cross-region replication
   - Tenant-specific recovery procedures
   - Regular recovery testing

## Best Practices

1. **Development**
   - Always test with multiple tenants
   - Use tenant context in development
   - Maintain tenant isolation in tests

2. **Deployment**
   - Zero-downtime deployments
   - Tenant-aware feature flags
   - Gradual rollouts

3. **Monitoring**
   - Per-tenant error tracking
   - Resource usage monitoring
   - Performance metrics

4. **Support**
   - Tenant context in logs
   - Support tools with tenant switching
   - Tenant-specific support channels 