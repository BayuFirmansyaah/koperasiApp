#!/bin/bash

# Koperasi App Auto-Deployment Script
# Usage: ./deploy.sh [environment]

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_NAME="koperasi-app"
REGISTRY="${REGISTRY:-}"
ENVIRONMENT="${1:-production}"
DEPLOY_PATH="/app"

echo -e "${YELLOW}=== Koperasi App Deployment ===${NC}"
echo "Environment: $ENVIRONMENT"
echo "App Name: $APP_NAME"
echo ""

# Function to log messages
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" >&2
}

# Step 1: Pull latest code
log "Step 1: Pulling latest code from repository..."
cd "$DEPLOY_PATH" || exit 1
git pull origin main || git pull origin master || error "Failed to pull code"

# Step 2: Build Docker image
log "Step 2: Building Docker image..."
docker build -t "$APP_NAME:latest" . || error "Failed to build Docker image"

if [ -n "$REGISTRY" ]; then
    log "Step 3: Pushing to registry..."
    docker tag "$APP_NAME:latest" "$REGISTRY/$APP_NAME:latest"
    docker push "$REGISTRY/$APP_NAME:latest" || error "Failed to push to registry"
fi

# Step 4: Stop old containers
log "Step 4: Stopping old containers..."
docker-compose down || true

# Step 5: Start new containers
log "Step 5: Starting new containers..."
docker-compose up -d || error "Failed to start containers"

# Step 6: Run migrations
log "Step 6: Running database migrations..."
docker-compose exec -T app php artisan migrate --force || error "Failed to run migrations"

# Step 7: Clear cache
log "Step 7: Clearing application cache..."
docker-compose exec -T app php artisan cache:clear || true
docker-compose exec -T app php artisan config:cache || true
docker-compose exec -T app php artisan view:cache || true

# Step 8: Verify deployment
log "Step 8: Verifying deployment..."
if docker-compose ps | grep -q "koperasi-app.*Up"; then
    log "✓ Application is running successfully"
else
    error "Application failed to start"
    exit 1
fi

log "✓ Deployment completed successfully!"
echo ""
echo "Next steps:"
echo "1. Verify the application is accessible"
echo "2. Check logs: docker-compose logs -f"
echo "3. Run tests: docker-compose exec app php artisan test"
