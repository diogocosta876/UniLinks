up:
	@docker-compose up
	@echo "https://localhost:8000" - UniLinks in HTTPS
	@echo "http://localhost:4321"  - phpMyAdmin
	@npm run watch

down:
	@docker-compose down

logs:
	@docker-compose logs -f

db:
	@php artisan db:wipe 
	@php artisan db:seed 
	@php artisan serve

