Instructions
============
1. php artisan migrate
	-Migrate all tables into db (Pigeon, Constraint, Order)
	
2. php artisan db:seed
	-Populate Pigeon and Constraint table with static data
	
3. php artisan serve
	-Start the local server at http://localhost:8000
	
	
Files touched
=============
Scheduled jobs:

1. app/Console/Kernel.php
	-This file contains the scheduled job to decrease the downtime timer for each pigeon hourly
	
Controllers:
	
1. app/Http/Controllers/OrderController.php
	-This controller manages incoming order related HTTP requests. It does validation and passes data to Order service for processing.
	
2. app/Http/Controllers/PigeonController.php
	-This controller manages incoming pigeon related HTTP requests. It does validation and passes data to Pigeon service for processing.
	
Models:

1. app/Models/Constraint.php
	-This file contains the Constraints Model and its relation with Pigeons, Constriants are attributes that pigeons are limited to. eg. range, downtime, speed..etc
	
2. app/Models/Order.php
	-This file contains the Order Model. An Order contains sufficient data for invocing/inventory for each delivery.

3. app/Models/Pigeon.php
	-This file contains the Pigen Model. The Pigeon Mddel contains all other pigeon attributes which arestatic. It also contains a 'ready' timer to signify if a pigeon is ready.
	
Services:

1. app/Services/OrderService
	-This service validates if an order is to be rejected and assign the order to the most eligible pigeon for the delivery

2. app/Services/PigeonService
	-This service adds a Pigeon
	
	
Routes:

1. app/api.php
	-This file contains routes for adding pigeons and to create a delivery order.
	
	
Migrations file:
2020_05_23_035018_create_constraint_table.php
2020_05_23_034952_create_order_table.php
2020_05_23_034930_create_pigeon_table.php

