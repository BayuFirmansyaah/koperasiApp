.PHONY: help build up down logs test deploy

help:
	@echo "Koperasi App Docker Commands"
	@echo ""
	@echo "Development:"
	@echo "  make build         - Build Docker images"
	@echo "  make up            - Start containers"
	@echo "  make down          - Stop containers"
	@echo "  make logs          - View container logs"
	@echo "  make shell         - Enter app container shell"
	@echo ""
	@echo "Database:"
	@echo "  make migrate       - Run database migrations"
	@echo "  make seed          - Run database seeder"
	@echo "  make migrate-fresh - Fresh migration with seed"
	@echo ""
	@echo "Testing:"
	@echo "  make test          - Run PHPUnit tests"
	@echo "  make test-feature  - Run feature tests only"
	@echo "  make test-unit     - Run unit tests only"
	@echo ""
	@echo "Deployment:"
	@echo "  make deploy        - Deploy to production"
	@echo "  make deploy-dev    - Deploy to development"

# Docker commands
build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

logs:
	docker-compose logs -f

shell:
	docker-compose exec app sh

# Database commands
migrate:
	docker-compose exec app php artisan migrate

seed:
	docker-compose exec app php artisan db:seed

migrate-fresh:
	docker-compose exec app php artisan migrate:fresh --seed

# Testing
test:
	docker-compose exec app php artisan test

test-feature:
	docker-compose exec app php artisan test --filter "Feature"

test-unit:
	docker-compose exec app php artisan test --filter "Unit"

# Laravel Artisan shortcuts
cache-clear:
	docker-compose exec app php artisan cache:clear

config-cache:
	docker-compose exec app php artisan config:cache

view-cache:
	docker-compose exec app php artisan view:cache

storage-link:
	docker-compose exec app php artisan storage:link

# Deployment
deploy:
	./deploy.sh production

deploy-dev:
	./deploy.sh development

# Cleanup
clean:
	docker system prune -f
	docker image prune -f

clean-all:
	docker system prune -a --volumes -f
