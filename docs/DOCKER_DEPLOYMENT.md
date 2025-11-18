# Docker Deployment Guide - Koperasi App

## Quick Start

### Prerequisites
- Docker & Docker Compose installed
- Git repository access
- Docker Hub account (optional, for image registry)

### Local Development

```bash
# Build images
make build

# Start containers
make up

# Run migrations
make migrate

# View logs
make logs
```

### Access Application
- App: http://localhost:80
- PHP-FPM: localhost:9000

## Production Deployment

### Option 1: Manual Deployment

```bash
# 1. Clone repository
git clone <repository> /path/to/app
cd /path/to/app

# 2. Create environment file
cp .env.example .env
# Edit .env with production values

# 3. Generate app key
docker-compose exec app php artisan key:generate

# 4. Run deployment script
chmod +x deploy.sh
./deploy.sh production

# 5. Verify
docker-compose ps
docker-compose logs app
```

### Option 2: CapRover Deployment

#### Setup CapRover App

1. **Create new app in CapRover dashboard:**
   - App Name: `koperasi-app`
   - Persistent directories:
     - `/app/storage`
     - `/app/bootstrap/cache`

2. **Upload Docker compose file:**
   ```bash
   # In CapRover dashboard, go to App Configs
   # Enable "Persistent Directories"
   # Set volume mount path: /app/storage
   ```

3. **Set environment variables in CapRover:**
   ```
   APP_NAME=Koperasi
   APP_ENV=production
   APP_KEY=<generated-key>
   APP_DEBUG=false
   APP_URL=<your-domain>
   LOG_CHANNEL=stack
   LOG_LEVEL=error
   DB_CONNECTION=sqlite
   DB_DATABASE=/app/database/database.sqlite
   ```

4. **Deployment from repository:**
   - Go to "App Configs" tab
   - Scroll to "Deploy from GitHub/GitLab"
   - Connect repository
   - Set branch to deploy (e.g., main)
   - Add deployment script:

   ```bash
   #!/bin/bash
   set -e
   
   # Pull latest code
   git pull origin main
   
   # Build image
   docker build -t koperasi-app:latest .
   
   # Run migrations
   docker-compose exec -T app php artisan migrate --force
   
   # Clear cache
   docker-compose exec -T app php artisan cache:clear
   docker-compose exec -T app php artisan config:cache
   
   echo "Deployment completed!"
   ```

#### Auto-Deploy Setup

Add webhook in GitHub/GitLab to trigger CapRover deployment on push:

**GitHub:**
1. Go to repository Settings > Webhooks
2. Add webhook URL from CapRover deployment config
3. Content type: `application/json`
4. Events: Push events only
5. Active: checked

**GitLab:**
1. Go to Project Settings > Webhooks
2. Add webhook URL
3. Trigger: Push events
4. Save

### Testing Deployment

```bash
# Run tests
make test

# Run feature tests
make test-feature

# Run unit tests
make test-unit

# Check application status
docker-compose ps

# View logs
docker-compose logs -f app
```

### Common Commands

```bash
# Database
make migrate              # Run migrations
make seed                 # Run seeders
make migrate-fresh        # Fresh migration + seed

# Cache
make cache-clear          # Clear application cache
make config-cache         # Cache configuration
make view-cache           # Cache views

# Shell access
make shell                # Enter app container
docker-compose exec app php artisan tinker  # Laravel Tinker

# Cleanup
make clean                # Prune system
make clean-all            # Prune everything including volumes
```

## Monitoring & Maintenance

### View Logs

```bash
# All services
docker-compose logs -f

# App only
docker-compose logs -f app

# Nginx only
docker-compose logs -f nginx

# Follow specific number of lines
docker-compose logs -f --tail=100 app
```

### Database Backup

```bash
# Backup SQLite database
docker-compose exec app cp database/database.sqlite database/backups/database-$(date +%Y%m%d-%H%M%S).sqlite

# Or use within container
docker-compose exec app sh -c 'cp database/database.sqlite database/database-backup.sqlite'
```

### Health Checks

```bash
# Check PHP-FPM
docker-compose exec app php-fpm-healthcheck

# Check database
docker-compose exec app php artisan db

# Check storage permissions
docker-compose exec app php artisan storage:link
```

## Troubleshooting

### Container won't start

```bash
# Check logs
docker-compose logs app

# Rebuild images
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Permission errors

```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data /app/storage
docker-compose exec app chmod -R 755 /app/storage
```

### Database locked

```bash
# Restart containers
docker-compose restart app

# Or full restart
docker-compose down
docker-compose up -d
```

### Out of disk space

```bash
# Clean up Docker
make clean-all

# Remove old images
docker image prune -a

# Remove stopped containers
docker container prune
```

## Security Best Practices

1. **Environment Variables:**
   - Never commit `.env` file
   - Use secure configuration management (CapRover secrets, etc.)
   - Rotate APP_KEY periodically

2. **Database:**
   - Backup database regularly
   - Use strong database credentials
   - Enable database encryption if available

3. **Updates:**
   - Regularly update base images
   - Run `composer update` in development
   - Test updates before production deployment

4. **Logs:**
   - Monitor error logs regularly
   - Implement log rotation
   - Use centralized logging for production

5. **SSL/TLS:**
   - Use HTTPS in production
   - Enable HSTS headers (already in nginx.conf)
   - Automatically renew certificates

## Performance Tuning

### PHP Configuration
Edit `docker/php.ini` to adjust:
- `memory_limit` - Default 256M
- `max_execution_time` - Default 300s
- `upload_max_filesize` - Default 20M

### Nginx Configuration
Edit `docker/vhost.conf` to adjust:
- `client_max_body_size` - Default 20M
- `keepalive_timeout` - Default 65s
- Gzip compression settings

### Docker Resources
Limit container resources in `docker-compose.yml`:
```yaml
services:
  app:
    deploy:
      resources:
        limits:
          cpus: '1'
          memory: 512M
```

## Support & Contact

For issues or questions:
1. Check logs: `make logs`
2. Run tests: `make test`
3. Check documentation
4. Contact development team
